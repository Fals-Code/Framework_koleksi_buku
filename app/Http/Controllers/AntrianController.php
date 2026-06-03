<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    public function pendaftaran()
    {
        return view('antrian.pendaftaran');
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'keperluan' => 'nullable|string|max:255',
        ]);

        $antrian = DB::transaction(function () use ($request) {
            return Antrian::create([
                'nomor_antrian' => Antrian::generateNomorAntrian(),
                'nama_pengunjung' => $request->nama_pengunjung,
                'nim' => $request->nim,
                'keperluan' => $request->keperluan,
                'tanggal_antrian' => today(),
                'waktu_daftar' => now(),
                'status' => 'menunggu',
            ]);
        });

        $this->touchQueue('register');

        return redirect()->route('antrian.nomor_saya', $antrian->id);
    }

    public function nomorSaya($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.nomor-saya', compact('antrian'));
    }

    public function admin()
    {
        $antrianMenunggu = Antrian::hariIni()->menunggu()->get();
        $antrianDipanggil = Antrian::hariIni()->dipanggil()->orderByDesc('waktu_dipanggil')->get();
        $antrianTerlewat = Antrian::hariIni()->terlewat()->get();

        return view('antrian.admin', compact('antrianMenunggu', 'antrianDipanggil', 'antrianTerlewat'));
    }

    public function panggil()
    {
        $antrian = DB::transaction(function () {
            Antrian::hariIni()
                ->dipanggil()
                ->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                ]);

            $antrian = Antrian::hariIni()
                ->menunggu()
                ->orderBy('waktu_daftar')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (!$antrian) {
                return null;
            }

            $antrian->update([
                'status' => 'dipanggil',
                'waktu_dipanggil' => now(),
                'waktu_selesai' => null,
            ]);

            return $antrian->fresh();
        });

        if ($antrian) {
            $this->touchQueue('call', $antrian);

            return response()->json(['success' => true, 'message' => 'Antrian dipanggil', 'data' => $antrian]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada antrian yang menunggu'], 404);
    }

    public function skip($id)
    {
        $antrian = Antrian::hariIni()->findOrFail($id);
        $antrian->update([
            'status' => 'terlewat',
            'waktu_selesai' => null,
        ]);

        $this->touchQueue('skip');

        return response()->json(['success' => true, 'message' => 'Antrian dilewati']);
    }

    public function panggilUlang($id)
    {
        $antrian = DB::transaction(function () use ($id) {
            Antrian::hariIni()
                ->dipanggil()
                ->where('id', '!=', $id)
                ->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                ]);

            $antrian = Antrian::hariIni()->lockForUpdate()->findOrFail($id);
            $antrian->update([
                'status' => 'dipanggil',
                'waktu_dipanggil' => now(),
                'waktu_selesai' => null,
            ]);

            return $antrian->fresh();
        });

        $this->touchQueue('call', $antrian);

        return response()->json(['success' => true, 'message' => 'Antrian dipanggil ulang', 'data' => $antrian]);
    }

    public function papan()
    {
        return view('antrian.papan');
    }

    public function stream(Request $request)
    {
        // SSE keeps the HTTP connection open while clients wait for
        // queue-update events, so PHP must not terminate the script early.
        set_time_limit(0);

        return response()->stream(function () {
            while (true) {
                if (connection_aborted()) {
                    break;
                }

                $data = $this->queuePayload();

                echo 'event: queue-update' . PHP_EOL;
                echo 'data: ' . json_encode($data) . PHP_EOL;
                echo PHP_EOL; // Blank line marks the end of one SSE message.

<<<<<<< HEAD
=======
                // Padding to bypass Apache/Ngrok buffering
                echo ': ' . str_repeat(' ', 4096) . PHP_EOL;

>>>>>>> 6971a8567b4f20cdd3de32b96134e7267a53c467
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                sleep(1);
            }
        }, 200, [
            'Content-Type'  => 'text/event-stream; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            // Required for Nginx/proxy setups so events are not buffered.
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function touchQueue(string $action, ?Antrian $currentCalled = null): void
    {
        // Every queue mutation updates this version value so all connected
        // EventSource clients receive the latest complete payload immediately.
        Cache::put('antrian_updated_at', microtime(true));
        Cache::put('antrian_action', $action);

        if ($currentCalled) {
            Cache::put('antrian_current_called_id', $currentCalled->id);
        }
    }

    private function queuePayload(): array
    {
        $currentCalledId = Cache::get('antrian_current_called_id');
        $currentCalled = $currentCalledId
            ? Antrian::hariIni()->dipanggil()->find($currentCalledId)
            : null;

        if (!$currentCalled) {
            $currentCalled = Antrian::hariIni()
                ->dipanggil()
                ->orderByDesc('waktu_dipanggil')
                ->first();
        }

        return [
            'menunggu' => Antrian::hariIni()->menunggu()->orderBy('waktu_daftar')->orderBy('id')->get(),
            'dipanggil' => Antrian::hariIni()->dipanggil()->orderByDesc('waktu_dipanggil')->get(),
            'terlewat' => Antrian::hariIni()->terlewat()->orderBy('updated_at')->get(),
            'riwayat_panggilan' => Antrian::hariIni()
                ->whereIn('status', ['dipanggil', 'selesai'])
                ->whereNotNull('waktu_dipanggil')
                ->orderByDesc('waktu_dipanggil')
                ->limit(6)
                ->get(),
            'current_called' => $currentCalled,
            'action' => Cache::get('antrian_action'),
            'updated_at' => Cache::get('antrian_updated_at', 0),
            'server_time' => now()->toDateTimeString(),
        ];
    }
}
