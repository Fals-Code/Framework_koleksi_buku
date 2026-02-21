<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    // TAMBAHKAN FUNGSI INI YANG HILANG
    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        $kategori = Kategori::create($request->all());
        
        auth()->user()->notify(new SystemNotification([
            'title' => 'Kategori Baru',
            'message' => 'Kategori "' . $kategori->nama_kategori . '" berhasil dibuat.',
            'link' => route('kategori.index'),
            'type' => 'success'
        ]));

        return redirect()->route('kategori.index');
    }

// Controller sudah benar jika isinya seperti ini:
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

        auth()->user()->notify(new SystemNotification([
            'title' => 'Update Kategori',
            'message' => 'Kategori diubah menjadi "' . $kategori->nama_kategori . '".',
            'link' => route('kategori.index'),
            'type' => 'info'
        ]));

        return redirect()->route('kategori.index');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $nama = $kategori->nama_kategori;
        $kategori->delete();

        auth()->user()->notify(new SystemNotification([
            'title' => 'Hapus Kategori',
            'message' => 'Kategori "' . $nama . '" dihapus.',
            'link' => route('kategori.index'),
            'type' => 'danger'
        ]));

        return redirect()->route('kategori.index');
    }
}