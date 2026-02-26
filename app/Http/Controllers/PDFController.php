<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Buku;
use App\Notifications\SystemNotification;

class PDFController extends Controller
{
    public function cetakLaporanBuku()
    {
        $bukus = Buku::with('kategori')->get();
        
        $data = [
            'title' => 'Laporan Inventaris Koleksi Buku',
            'date' => date('d/m/Y'),
            'bukus' => $bukus
        ];


        if (auth()->check()) {
            auth()->user()->notify(new SystemNotification([
                'title' => 'Cetak Laporan',
                'message' => 'Laporan data buku PDF berhasil dihasilkan.',
                'link' => '#',
                'type' => 'info'
            ]));
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.laporan_buku', $data)->setPaper('a4', 'portrait');
        
        return $pdf->stream('Laporan_Data_Buku_' . date('Ymd') . '.pdf');
    }

    public function cetakLabelBuku()
    {
        $bukus = Buku::all();

        if (auth()->check()) {
            auth()->user()->notify(new SystemNotification([
                'title' => 'Cetak Label',
                'message' => 'Label barcode buku berhasil diproses.',
                'link' => '#',
                'type' => 'info'
            ]));
        }

        $pdf = Pdf::loadView('pdf.label_buku', compact('bukus'));
        
        $pdf->setPaper([0, 0, 283.46, 141.73], 'portrait');
        
        return $pdf->stream('Label_Buku_' . date('Ymd') . '.pdf');
    }
}