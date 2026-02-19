<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        return view('buku.index', compact('bukus'));
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
            'idkategori' => 'required|exists:kategoris,idkategori',
        ]);

        $buku = Buku::create($request->all());
        $this->addNotification('Buku Baru', 'Buku "' . $buku->judul . '" berhasil ditambahkan.');
        
        return redirect()->route('buku.index');
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
            'idkategori' => 'required|exists:kategoris,idkategori',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());
        $this->addNotification('Update Buku', 'Data buku "' . $buku->judul . '" telah diperbarui.');

        return redirect()->route('buku.index');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $judul = $buku->judul;
        $buku->delete();
        $this->addNotification('Hapus Buku', 'Buku "' . $judul . '" telah dihapus.');

        return redirect()->route('buku.index');
    }

    public function getNextKode($idkategori)
    {
        $count = Buku::where('idkategori', $idkategori)->count();
        $kategori = Kategori::find($idkategori);
        if (!$kategori) return response()->json(['kode' => '']);
        $nama = strtoupper($kategori->nama_kategori);
        $inisial = (strlen($nama) >= 3) ? $nama[0].$nama[2] : substr($nama, 0, 2);
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        return response()->json(['kode' => $inisial . '-' . $nextNumber]);
    }

    private function addNotification($title, $message)
    {
        $notifications = session()->get('notifications', []);
        array_unshift($notifications, [
            'title' => $title,
            'message' => $message,
            'time' => now()->format('H:i'),
            'unread' => true
        ]);
        session()->put('notifications', array_slice($notifications, 0, 5));
    }
}