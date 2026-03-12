<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $barang = DB::table('barang')->where('id_barang', $kode)->first();

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

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'code'    => 200
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
                'code'    => 500
            ], 500);
        }
    }
}
