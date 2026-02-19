<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Buku;

class PDFController extends Controller
{
    public function cetakSertifikat()
    {
        $data = [
            'nomor_sertifikat' => '2026/TECH-DEV/AI/' . rand(1000, 9999),
            'nama_penerima'    => 'ALFIA RIZQY HANIFAH, S.KOM.',
            'peran'            => 'FULL-STACK WEB DEVELOPER',
            'tema_acara'       => 'Mastering Laravel 11 & Gemini AI Integration',
            'tanggal_acara'    => '19 Februari 2026',
        ];

        $this->addNotification('Cetak Sertifikat', 'Sertifikat sedang diproses. Silakan cek tab baru browser Anda.');

        $pdf = Pdf::loadView('pdf.sertifikat', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Sertifikat_Pelatihan.pdf');
    }

    public function cetakUndangan()
    {
        $data = [
            'nomor_surat'   => '005/FIKKIA/UNAIR/II/2026',
            'perihal'       => 'Undangan Seminar Nasional Teknologi AI',
            'tanggal_surat' => '19 Februari 2026',
            'nama_tujuan'   => 'Bapak/Ibu Dosen FIKKIA',
            'tempat_tujuan' => 'Tempat',
            'hari_acara'    => 'Senin',
            'tgl_acara'     => '2 Maret 2026',
            'waktu_acara'   => '09.00 - Selesai',
            'lokasi_acara'  => 'Aula Kampus FIKKIA Universitas Airlangga',
        ];

        $this->addNotification('Cetak Undangan', 'Surat undangan sedang diproses dan akan segera ditampilkan.');

        $pdf = Pdf::loadView('pdf.undangan', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('Surat_Undangan.pdf');
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