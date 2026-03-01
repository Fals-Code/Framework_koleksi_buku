<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
{
    $totalAsset = DB::table('barang')->count();
    $totalNilai = DB::table('barang')->sum('harga') ?? 0;
    $termahal = DB::table('barang')->orderBy('harga', 'desc')->first();
    $termurah = DB::table('barang')->orderBy('harga', 'asc')->first();

    $stats = [
        'total_asset' => $totalAsset,
        'total_nilai' => $totalNilai,
        'termahal'    => $termahal,
        'termurah'    => $termurah,
    ];

    $labels = [];
    $totals = [];
    
    $hasTimestamp = Schema::hasColumn('barang', 'timestamp');

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $labels[] = $date->format('d M'); 
        
        if ($hasTimestamp) {
            $count = DB::table('barang')
                        ->whereDate('timestamp', $date->format('Y-m-d'))
                        ->count();
        } else {
            $count = 0;
        }
        $totals[] = $count;
    }

    $totalBuku = Schema::hasTable('bukus') ? DB::table('bukus')->count() : 0;
    $totalKategori = Schema::hasTable('kategoris') ? DB::table('kategoris')->count() : 0;

    return view('home', compact('stats', 'totalBuku', 'totalKategori', 'labels', 'totals'));
}

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()->back()->with('success', 'Semua notifikasi berhasil dibersihkan!');
    }
}