<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\SystemNotification;

class PenjualanController extends Controller
{
    /**
     * Tampilkan halaman Kasir POS
     */
    public function index()
    {
        return view('penjualan.kasir');
    }

    /**
     * Cari barang berdasarkan kode/id_barang (dipanggil via Axios GET)
     */
    public function cariBarang($kode)
    {
        // Mencari berdasarkan kolom barcode atau id_barang (jika barcode tidak ada)
        $barang = DB::table('barang')
            ->where('barcode', $kode)
            ->orWhere('id_barang', $kode)
            ->first();

        if ($barang) {
            return response()->json([
                'status'  => 'success',
                'data'    => $barang
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Barang dengan kode "' . $kode . '" tidak ditemukan.'
        ], 404);
    }

    /**
     * Search barang based on query (for autocomplete)
     */
    public function searchBarang(Request $request)
    {
        $query = $request->get('query');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $barang = DB::table('barang')
            ->where('barcode', 'LIKE', "%{$query}%")
            ->orWhere('id_barang', 'LIKE', "%{$query}%")
            ->orWhere('nama', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($barang);
    }

    /**
     * Simpan transaksi penjualan menggunakan DB Transaction
     */
    public function simpan(Request $request)
    {
        $request->validate([
            'items'         => 'required|array|min:1',
            'items.*.kode'  => 'required|string',
            'items.*.jumlah'   => 'required|integer|min:1',
            'items.*.subtotal' => 'required|integer|min:0',
            'total'         => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke tabel penjualan
            $penjualan = new Penjualan();
            $penjualan->total = $request->total;
            $penjualan->timestamp = now();
            $penjualan->save();

            // 2. Loop simpan detail ke tabel penjualan_detail
            foreach ($request->items as $item) {
                $detail = new PenjualanDetail();
                $detail->id_penjualan = $penjualan->id_penjualan;
                $detail->id_barang    = $item['kode'];
                $detail->jumlah       = $item['jumlah'];
                $detail->subtotal     = $item['subtotal'];
                $detail->save();
            }

            DB::commit();

            auth()->user()->notify(new SystemNotification([
                'title' => 'Transaksi Berhasil',
                'message' => 'Penjualan Rp ' . number_format($request->total, 0, ',', '.') . ' (' . count($request->items) . ' item) berhasil disimpan.',
                'link' => route('kasir.index'),
                'type' => 'success'
            ]));

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'code'    => 200
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            auth()->user()->notify(new SystemNotification([
                'title' => 'Transaksi Gagal',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
                'link' => route('kasir.index'),
                'type' => 'danger'
            ]));

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
                'code'    => 500
            ], 500);
        }
    }
}
