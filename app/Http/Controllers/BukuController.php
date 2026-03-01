<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;

class BukuController extends Controller
{
public function index()
{
    $bukus = Buku::with('kategori')->latest()->get();
    $kategoris = \App\Models\Kategori::all();

    return view('buku.index', compact('bukus', 'kategoris'));
}

    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'idkategori' => 'required|exists:kategoris,id',
        ]);

        $buku = Buku::create($request->all());

        auth()->user()->notify(new SystemNotification([
            'title' => 'Buku Baru',
            'message' => 'Buku "' . $buku->judul . '" berhasil ditambahkan.',
            'link' => route('buku.index'),
            'type' => 'success'
        ]));
        
        return redirect()->route('buku.index')
            ->with('success', 'Buku baru berhasil disimpan ke sistem!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'idkategori' => 'required|exists:kategoris,id',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        auth()->user()->notify(new SystemNotification([
            'title' => 'Update Buku',
            'message' => 'Data buku "' . $buku->judul . '" telah diperbarui.',
            'link' => route('buku.index'),
            'type' => 'info'
        ]));

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $judul = $buku->judul;
        $buku->delete();

        auth()->user()->notify(new SystemNotification([
            'title' => 'Hapus Buku',
            'message' => 'Buku "' . $judul . '" telah dihapus.',
            'link' => route('buku.index'),
            'type' => 'danger'
        ]));

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus dari sistem.');
    }

    public function getNextKode($idkategori)
    {
        $count = Buku::where('idkategori', $idkategori)->count();
        $kategori = Kategori::find($idkategori);
        
        if (!$kategori) return response()->json(['kode' => '']);
        
        $nama = strtoupper($kategori->nama_kategori);
        $inisial = (strlen($nama) >= 3) ? $nama[0].$nama[2] : substr($nama, 0, 2);
        $nextNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        return response()->json(['kode' => $inisial . '-' . $nextNumber]);
    }

public function cetakLabel(Request $request)
{
    $ids = $request->query('id');
    
    if ($ids) {
        $idArray = explode(',', $ids);
        $bukus = Buku::with('kategori')->whereIn('id', $idArray)->get();
    } else {
        $bukus = Buku::with('kategori')->get();
    }

    return view('buku.cetak_label', compact('bukus'));
}

public function bulkDelete(Request $request)
{
    $ids = $request->ids;
    if ($ids) {
        // Hapus buku berdasarkan array ID yang dikirim
        Buku::whereIn('id', explode(',', $ids))->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku terpilih berhasil dihapus.'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Tidak ada data yang dipilih.'
    ], 400);
}
}