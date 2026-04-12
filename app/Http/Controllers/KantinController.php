<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Pesanan;
use App\Services\MidtransService;
use App\Services\OrderService;
use App\Http\Requests\Kantin\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KantinController extends Controller
{
    protected $midtransService;
    protected $orderService;

    public function __construct(MidtransService $midtransService, OrderService $orderService)
    {
        $this->midtransService = $midtransService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $vendors = Vendor::with('menu')->get();
        return view('kantin.customer.order', compact('vendors'));
    }

    public function checkout(CheckoutRequest $request)
    {
        try {
            // 1. Create order in DB via Service
            $pesanan = $this->orderService->createOrder($request->validated());

            // 2. Prepare Midtrans Payload
            $midtransItemDetails = $pesanan->detailPesanan->map(function ($detail) {
                return [
                    'id'       => (string) $detail->menu_id,
                    'price'    => (int) $detail->menu->harga,
                    'quantity' => (int) $detail->qty,
                    'name'     => substr($detail->menu->nama_makanan ?? $detail->menu->nama_menu, 0, 50),
                ];
            })->toArray();

            $params = [
                'transaction_details' => [
                    'order_id'     => $pesanan->nomor_pesanan,
                    'gross_amount' => (int) $pesanan->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $pesanan->nama_pelanggan,
                    'email'      => Auth::user()->email ?? 'guest@example.com',
                ],
                'item_details' => $midtransItemDetails,
            ];

            // 3. Get Snap Token
            $snapToken = $this->midtransService->getSnapToken($params);
            
            $pesanan->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken, 
                'order_id'   => $pesanan->id
            ]);

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error'   => 'checkout_failed'
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $notif = $this->midtransService->verifyCallback();

        if (!$notif) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('nomor_pesanan', $notif->order_id)->first();

        if ($pesanan) {
            $this->orderService->handleStatusUpdate($pesanan, $notif->transaction_status);
        }

        return response()->json(['status' => 'success']);
    }

    public function status($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status == 'pending') {
            $statusData = $this->midtransService->getStatus($pesanan->nomor_pesanan);
            
            if ($statusData) {
                $transactionStatus = is_object($statusData) 
                    ? $statusData->transaction_status 
                    : ($statusData['transaction_status'] ?? null);

                if ($transactionStatus) {
                    $this->orderService->handleStatusUpdate($pesanan, $transactionStatus);
                }
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
