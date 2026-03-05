<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
public function index(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('barang')->select(['id_barang', 'nama', 'harga', 'timestamp']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function($row) {
                return '<input type="checkbox" value="'.$row->id_barang.'" class="barang-checkbox">';
            })
            ->editColumn('harga', function($row) {
                return 'Rp ' . number_format($row->harga, 0, ',', '.');
            })
            ->editColumn('timestamp', function($row) {
                $carbon = \Carbon\Carbon::parse($row->timestamp);
                $dot = $carbon->isToday() ? '<span class="badge-dot shadow-sm me-2"></span>' : '';
                return $dot . '<span class="fw-bold text-dark">' . $carbon->format('d M Y') . '</span><br>' .
                       '<small class="text-muted" style="font-size: 11px;">' . $carbon->diffForHumans() . '</small>';
            })
            ->rawColumns(['checkbox', 'timestamp'])
            ->make(true);
    }
    return view('barang.index');
}

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|numeric',
        ]);

        try {
            Barang::create([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'timestamp' => now()
            ]);

            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

public function cetakLabel(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'x_coord' => 'required|integer|min:1|max:5',
        'y_coord' => 'required|integer|min:1|max:8',
    ]);

    $ids = $request->ids;
    $items = Barang::whereIn('id_barang', $ids)
             ->orderByRaw("FIELD(id_barang, " . implode(',', $ids) . ")")
             ->get();
    
    // Logika ini sudah benar: X1,Y1 = 0 skip. X2,Y1 = 1 skip.
    $skipSlots = (($request->y_coord - 1) * 5) + ($request->x_coord - 1);

    $pdf = Pdf::loadView('pdf.tnj108', [
        'items' => $items,
        'skipSlots' => $skipSlots
    ]);

    // Paksa ukuran A4
    return $pdf->setPaper('a4', 'portrait')->stream('Tag_Harga.pdf');
}
    
public function destroy($id)
{
    $deleted = DB::table('barang')->where('id_barang', $id)->delete();

    if ($deleted) {
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    return response()->json(['message' => 'Gagal menghapus data'], 404);
}

public function update(Request $request, $id)
{
    try {
        // Validasi data (Sesuai modul: Nama & Harga Required)
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric'
        ]);

        // Gunakan DB Table 'barang' dan pastikan primary key-nya 'id_barang'
        $affected = \DB::table('barang')
            ->where('id_barang', $id)
            ->update([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'timestamp' => now() // Gunakan kolom timestamp Anda
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diubah'
        ]);
        
    } catch (\Exception $e) {
        // Ini akan mengirimkan pesan error asli ke console log browser jika gagal
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
}