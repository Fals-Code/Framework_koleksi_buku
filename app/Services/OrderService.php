<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * Create a new order with details.
     *
     * @param array $data
     * @return Pesanan
     * @throws \Exception
     */
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $vendor = Vendor::findOrFail($data['vendor_id']);
            $totalHarga = 0;
            $orderItems = [];

            // 1. Validate items and calculate total
            foreach ($data['items'] as $item) {
                $menu = Menu::findOrFail($item['id']);
                $quantity = (int) $item['quantity'];

                if ($menu->stok < $quantity) {
                    throw new \Exception('Stok tidak mencukupi untuk ' . ($menu->nama_makanan ?? $menu->nama_menu));
                }

                $subtotal = $menu->harga * $quantity;
                $totalHarga += $subtotal;

                $orderItems[] = [
                    'menu_id'  => $menu->id,
                    'qty'      => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            // 2. Create the main Order
            $pesanan = Pesanan::create([
                'user_id'        => Auth::id() ?? 1,
                'vendor_id'      => $vendor->id,
                'nama_pelanggan' => $data['nama_pelanggan'],
                'nomor_pesanan'  => 'ORD-' . strtoupper(uniqid()),
                'total_harga'    => $totalHarga,
                'catatan'        => $data['catatan'] ?? null,
                'status'         => 'pending'
            ]);

            // 3. Create Order Details
            foreach ($orderItems as $oi) {
                DetailPesanan::create(array_merge($oi, ['pesanan_id' => $pesanan->id]));
            }

            return $pesanan->load('detailPesanan.menu');
        });
    }

    /**
     * Handle status update for an order.
     *
     * @param Pesanan $pesanan
     * @param string $midtransStatus
     * @return Pesanan
     */
    public function handleStatusUpdate(Pesanan $pesanan, string $midtransStatus)
    {
        return DB::transaction(function () use ($pesanan, $midtransStatus) {
            $oldStatus = $pesanan->status;
            $newStatus = $this->mapMidtransStatusToInternal($midtransStatus);

            if ($oldStatus !== $newStatus) {
                $pesanan->status = $newStatus;
                $pesanan->save();

                // Decrement stock ONLY if turning to 'completed'
                if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                    $this->decrementStock($pesanan);
                }
            }

            return $pesanan;
        });
    }

    /**
     * Map Midtrans transaction status to internal app status.
     *
     * @param string $status
     * @return string
     */
    protected function mapMidtransStatusToInternal(string $status)
    {
        return match ($status) {
            'settlement', 'capture' => 'completed',
            'pending'               => 'pending',
            'expire', 'cancel', 'deny' => 'cancelled',
            default                 => 'pending',
        };
    }

    /**
     * Decrement stock for each item in the order.
     *
     * @param Pesanan $pesanan
     */
    protected function decrementStock(Pesanan $pesanan)
    {
        foreach ($pesanan->detailPesanan as $detail) {
            $menu = $detail->menu;
            if ($menu) {
                $menu->decrement('stok', $detail->qty);
                Log::info("Stock decremented for menu ID $menu->id by $detail->qty");
            }
        }
    }
}
