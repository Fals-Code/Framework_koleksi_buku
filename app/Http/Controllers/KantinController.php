<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KantinController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function index()
    {
        $vendors = Vendor::with('menu')->get();
        return view('kantin.customer.order', compact('vendors'));
    }

    public function checkout(Request $request)
    {
        try {
            // Validasi request secara manual agar bisa menangkap error database atau data
            $validator = \Validator::make($request->all(), [
                'vendor_id'        => 'required|exists:vendors,id',
                'nama_pelanggan'   => 'required|string|max:255',
                'catatan'          => 'nullable|string',
                'items'            => 'required|array',
                'items.*.id'       => 'required|exists:menus,id',
                'items.*.quantity' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $vendor = Vendor::findOrFail($request->vendor_id);
            $total_harga = 0;
            $order_items_data = [];
            $midtrans_item_details = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['id']);
                $quantity = (int) $item['quantity'];

                // VALIDASI STOK
                if ($menu->stok < $quantity) {
                    return response()->json([
                        'message' => 'Stok tidak mencukupi untuk ' . ($menu->nama_makanan ?? $menu->nama_menu),
                        'error'   => 'insufficient_stock',
                        'menu_id' => $menu->id
                    ], 422);
                }

                $price = (int) $menu->harga;
                $subtotal = $price * $quantity;
                
                $total_harga += $subtotal;

                // Siapkan data untuk DB
                $order_items_data[] = [
                    'menu_id'  => $menu->id,
                    'qty'      => $quantity,
                    'subtotal' => $subtotal,
                ];

                // Siapkan data untuk Midtrans
                $midtrans_item_details[] = [
                    'id'       => (string) $menu->id,
                    'price'    => $price,
                    'quantity' => $quantity,
                    'name'     => substr($menu->nama_makanan ?? $menu->nama_menu ?? 'Item', 0, 50),
                ];
            }

            // Buat record pesanan di database
            // Pastikan Auth menggunakan guard yang tepat jika ada
            $userId = \Auth::id() ?? 1; // Fallback ke 1 untuk testing jika guest diperbolehkan

            $pesanan = Pesanan::create([
                'user_id'        => $userId,
                'vendor_id'      => $vendor->id,
                'nama_pelanggan' => $request->nama_pelanggan,
                'nomor_pesanan'  => 'ORD-' . strtoupper(uniqid()),
                'total_harga'    => $total_harga,
                'catatan'        => $request->catatan,
                'status'         => 'pending'
            ]);

            // Simpan Detail
            foreach ($order_items_data as $oi) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id'    => $oi['menu_id'],
                    'qty'        => $oi['qty'],
                    'subtotal'   => $oi['subtotal']
                ]);
            }

            // Payload Midtrans
            $params = [
                'transaction_details' => [
                    'order_id'     => $pesanan->nomor_pesanan,
                    'gross_amount' => (int) $total_harga,
                ],
                'customer_details' => [
                    'first_name' => $request->nama_pelanggan,
                    'email'      => \Auth::user()->email ?? 'guest@example.com',
                ],
                'item_details' => $midtrans_item_details,
            ];

            // Re-konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
            \Midtrans\Config::$is3ds = (bool) config('midtrans.is_3ds', true);

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $pesanan->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken, 
                'order_id'   => $pesanan->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Midtrans Checkout Error: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        $pesanan = Pesanan::where('nomor_pesanan', $order_id)->first();

        if ($pesanan) {
            $this->handleStatusUpdate($pesanan, $transaction);
        }

        return response()->json(['status' => 'success']);
    }

    public function status($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // If status is still pending, try to check directly with Midtrans
        if ($pesanan->status == 'pending') {
            try {
                // Set Midtrans Config (using same logic as checkout)
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
                \Midtrans\Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
                \Midtrans\Config::$is3ds = (bool) config('midtrans.is_3ds', true);

                $status = \Midtrans\Transaction::status($pesanan->nomor_pesanan);
                
                // Handle both object and array responses
                $transactionStatus = is_object($status) ? $status->transaction_status : ($status['transaction_status'] ?? null);

                $this->handleStatusUpdate($pesanan, $transactionStatus);
            } catch (\Exception $e) {
                \Log::warning('Manual Midtrans Status Check Failed for ID ' . $id . ': ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => $pesanan->status,
            'status_label' => ucfirst($pesanan->status)
        ]);
    }

    public function orderSuccess($id)
    {
        $pesanan = Pesanan::with(['vendor', 'detailPesanan.menu'])->findOrFail($id);
        return view('kantin.customer.success', compact('pesanan'));
    }

    private function handleStatusUpdate($pesanan, $transactionStatus)
    {
        $oldStatus = $pesanan->status;
        $newStatus = $oldStatus;

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            $newStatus = 'paid';
        } elseif ($transactionStatus == 'pending') {
            $newStatus = 'pending';
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $newStatus = 'cancelled';
        }

        if ($oldStatus != $newStatus) {
            $pesanan->update(['status' => $newStatus]);

            // KURANGI STOK HANYA JIKA PINDAH KE PAID
            if ($newStatus == 'paid') {
                foreach ($pesanan->detailPesanan as $detail) {
                    $menu = $detail->menu;
                    if ($menu) {
                        $menu->decrement('stok', $detail->qty);
                    }
                }
            }
        }
    }
    public function track($id)
    {
        $pesanan = Pesanan::with(['vendor', 'detailPesanan.menu'])->findOrFail($id);
        return view('kantin.customer.track', compact('pesanan'));
    }

    public function receipt($id)
    {
        $pesanan = Pesanan::with(['vendor', 'detailPesanan.menu'])->findOrFail($id);
        return view('kantin.customer.receipt', compact('pesanan'));
    }

    public function history()
    {
        $pesanan = Pesanan::with(['vendor', 'detailPesanan.menu'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('kantin.customer.history', compact('pesanan'));
    }
}
