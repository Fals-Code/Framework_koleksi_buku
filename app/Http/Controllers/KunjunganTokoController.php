<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;

class KunjunganTokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tokos = Toko::all();
        return view('kunjungan_toko.index', compact('tokos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kunjungan_toko.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|unique:tokos,barcode',
            'nama_toko' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
        ]);

        Toko::create($request->all());

        return redirect()->route('kunjungan-toko.index')->with('success', 'Toko berhasil ditambahkan!');
    }

    /**
     * Show the form for store visit (barcode scanning).
     */
    public function kunjungan()
    {
        return view('kunjungan_toko.kunjungan');
    }

    /**
     * Process the store visit data.
     */
    public function prosesKunjungan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
        ]);

        $toko = Toko::where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Toko dengan barcode tersebut tidak ditemukan.'
            ], 404);
        }

        // Calculate Haversine distance
        $jarakAktual = $this->haversine($toko->latitude, $toko->longitude, $request->latitude, $request->longitude);
        
        // Define max threshold distance
        $threshold = 100; // 100 meters
        
        // Calculate effective threshold based on accuracy
        $thresholdEfektif = $threshold + $toko->accuracy + $request->accuracy;

        if ($jarakAktual <= $thresholdEfektif) {
            return response()->json([
                'success' => true,
                'status' => 'DITERIMA',
                'message' => 'Kunjungan Diterima!',
                'jarak_aktual' => round($jarakAktual, 2),
                'threshold_efektif' => round($thresholdEfektif, 2),
                'toko' => $toko
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'DITOLAK',
                'message' => 'Kunjungan Ditolak. Posisi Anda terlalu jauh dari toko.',
                'jarak_aktual' => round($jarakAktual, 2),
                'threshold_efektif' => round($thresholdEfektif, 2),
                'toko' => $toko
            ]);
        }
    }

    /**
     * Pseudocode Formula Haversine implementation
     */
    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $r = 6371000; // Radius bumi dalam meter
        
        // Konversi derajat ke radian
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $r * $c;
    }
}
