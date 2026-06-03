<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NfcCard;
use App\Models\Peminjaman;
use App\Models\Kunjungan;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NfcController extends Controller
{
    /**
     * Halaman Utama Scanner NFC
     * Berisi 3 mode: Peminjaman, Pengembalian, Absensi
     */
    public function index()
    {
        $bukus = Buku::orderBy('judul', 'asc')->get();
        return view('nfc.index', compact('bukus'));
    }

    /**
     * Halaman Tulis Kartu NFC (Registrasi)
     */
    public function writeCard()
    {
        return view('nfc.write');
    }

    /**
     * Halaman Manajemen Data Kartu
     */
    public function cards()
    {
        $cards = NfcCard::with('user')->orderBy('created_at', 'desc')->get();
        return view('nfc.cards', compact('cards'));
    }

    /**
     * Simpan data kartu NFC baru ke database
     */
    public function storeCard(Request $request)
    {
        $data = $request->validate([
            'serial_number' => 'required|string|max:100|unique:nfc_cards,serial_number',
            'nama_anggota' => 'required|string|max:200',
            'nim' => 'nullable|string|max:50',
            'email' => 'nullable|email',
        ]);

        try {
            DB::beginTransaction();
            $card = NfcCard::create($data);
            DB::commit();

            Log::info('NFC card registered', [
                'id' => $card->id,
                'serial' => $card->serial_number,
                'by' => auth()->id() ?? 'system'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kartu NFC berhasil diregistrasi.',
                'id' => $card->id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('NFC store query error', ['error' => $e->getMessage(), 'data' => $data]);

            // Jika terjadi pelanggaran constraint unik, beri pesan spesifik
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Serial number sudah terdaftar.'
                ], 409);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('NFC store error', ['error' => $e->getMessage(), 'data' => $data]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem.'
            ], 500);
        }
    }

    /**
     * API: Cari data kartu & status peminjaman dari Serial Number
     */
    public function lookupCard(Request $request)
    {
        $serialNumber = $request->serial_number;

        $card = NfcCard::where('serial_number', $serialNumber)->first();

        if (!$card) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu NFC tidak terdaftar dalam sistem.'
            ], 404);
        }

        if (!$card->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu NFC ini sedang dinonaktifkan.'
            ], 403);
        }

        // Ambil data buku yang sedang dipinjam (jika ada)
        $activeBorrow = Peminjaman::where('nfc_card_id', $card->id)
            ->where('status', 'dipinjam')
            ->with('buku')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'card_id' => $card->id,
                'nama' => $card->nama_anggota,
                'nim' => $card->nim,
                'active_borrow' => $activeBorrow
            ]
        ]);
    }

    /**
     * API: Proses Peminjaman Buku
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'nfc_card_id' => 'required|exists:nfc_cards,id',
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $cardId = $request->nfc_card_id;

        // Cek apakah user masih memiliki buku yang dipinjam
        $hasActiveBorrow = Peminjaman::where('nfc_card_id', $cardId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($hasActiveBorrow) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota masih memiliki buku yang belum dikembalikan.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            Peminjaman::create([
                'nfc_card_id' => $cardId,
                'buku_id' => $request->buku_id,
                'tanggal_pinjam' => now(),
                'status' => 'dipinjam',
                'petugas' => auth()->user() ? auth()->user()->name : 'Sistem NFC'
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman berhasil dicatat.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem.'
            ], 500);
        }
    }

    /**
     * API: Proses Pengembalian Buku
     */
    public function storePengembalian(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        if ($peminjaman->status === 'dikembalikan') {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku ini sudah dikembalikan sebelumnya.'
            ], 400);
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengembalian buku berhasil dicatat.'
        ]);
    }

    /**
     * API: Proses Absensi Kunjungan
     */
    public function storeKunjungan(Request $request)
    {
        $request->validate([
            'nfc_card_id' => 'required|exists:nfc_cards,id',
        ]);

        $cardId = $request->nfc_card_id;

        // Cek apakah ada kunjungan hari ini yang belum tap keluar
        $activeVisit = Kunjungan::where('nfc_card_id', $cardId)
            ->whereNull('waktu_keluar')
            ->whereDate('waktu_masuk', today())
            ->first();

        if ($activeVisit) {
            // Jika ada, jadikan tap keluar
            $activeVisit->update([
                'waktu_keluar' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'action' => 'out',
                'message' => 'Berhasil mencatat Tap Keluar kunjungan.'
            ]);
        } else {
            // Jika tidak, jadikan tap masuk
            Kunjungan::create([
                'nfc_card_id' => $cardId,
                'waktu_masuk' => now(),
                'tujuan' => 'Kunjungan Perpustakaan'
            ]);

            return response()->json([
                'status' => 'success',
                'action' => 'in',
                'message' => 'Berhasil mencatat Tap Masuk kunjungan.'
            ]);
        }
    }

    /**
     * Halaman Riwayat Transaksi NFC
     */
    public function history()
    {
        $peminjamans = Peminjaman::with(['nfcCard', 'buku'])->orderBy('created_at', 'desc')->get();
        $kunjungans = Kunjungan::with('nfcCard')->orderBy('created_at', 'desc')->get();

        return view('nfc.history', compact('peminjamans', 'kunjungans'));
    }
}
