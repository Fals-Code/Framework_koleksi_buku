<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        $kategori = Kategori::create($request->all());
        $this->addNotification('Kategori Baru', 'Kategori "' . $kategori->nama_kategori . '" berhasil dibuat.');
        return redirect()->route('kategori.index');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());
        $this->addNotification('Update Kategori', 'Kategori diubah menjadi "' . $kategori->nama_kategori . '".');
        return redirect()->route('kategori.index');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $nama = $kategori->nama_kategori;
        $kategori->delete();
        $this->addNotification('Hapus Kategori', 'Kategori "' . $nama . '" dihapus.');
        return redirect()->route('kategori.index');
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