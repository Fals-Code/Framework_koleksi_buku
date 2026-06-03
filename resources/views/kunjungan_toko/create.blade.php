@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Titik Awal Toko</h4>
                <p class="card-description">Ambil titik lokasi untuk didaftarkan ke database.</p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('kunjungan-toko.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input type="text" name="nama_toko" class="form-control" required placeholder="Contoh: Toko Maju Jaya">
                    </div>
                    
                    <div class="form-group">
                        <label>Barcode / ID Toko</label>
                        <input type="text" name="barcode" class="form-control" required placeholder="Contoh: TK-001">
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center mb-3">
                            <button type="button" class="btn btn-warning btn-icon-text" id="btnAmbilLokasi" onclick="mulaiAmbilLokasi()">
                                <i class="mdi mdi-crosshairs-gps btn-icon-prepend"></i> Ambil Lokasi Akurat Saat Ini
                            </button>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="text" name="latitude" id="inputLat" class="form-control" readonly required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="text" name="longitude" id="inputLng" class="form-control" readonly required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Accuracy (Meter)</label>
                                <input type="text" name="accuracy" id="inputAcc" class="form-control" readonly required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2 mt-3" id="btnSubmit" disabled>Simpan Data Toko</button>
                    <a href="{{ route('kunjungan-toko.index') }}" class="btn btn-light mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    // Lampiran 1: Fungsi JS untuk ambil lokasi
    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) { 
        return new Promise((resolve, reject) => { 
            let bestResult = null; 
            const startTime = Date.now(); 
            const watchId = navigator.geolocation.watchPosition( 
                (position) => { 
                    const acc = position.coords.accuracy; 
                    // Simpan hasil terbaik sejauh ini 
                    if (!bestResult || acc < bestResult.coords.accuracy) { 
                        bestResult = position; 
                    } 
                    // Kalau sudah cukup akurat, berhenti 
                    if (acc <= targetAccuracy) { 
                        navigator.geolocation.clearWatch(watchId); 
                        resolve(bestResult); 
                    } 
                    // Kalau timeout, pakai hasil terbaik yang ada 
                    if (Date.now() - startTime >= maxWait) { 
                        navigator.geolocation.clearWatch(watchId); 
                        if (bestResult) resolve(bestResult); 
                        else reject(new Error("Timeout, tidak dapat posisi")); 
                    } 
                }, 
                (error) => reject(error), 
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait } 
            ); 
        }); 
    } 

    async function mulaiAmbilLokasi() {
        const btn = document.getElementById('btnAmbilLokasi');
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin btn-icon-prepend"></i> Sedang Mencari Sinyal GPS...';
        btn.disabled = true;
        
        try {
            Swal.fire({
                title: 'Menemukan Lokasi...',
                text: 'Mohon tunggu, sedang mencari titik kordinat paling akurat.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const pos = await getAccuratePosition(50, 20000); 
            
            document.getElementById('inputLat').value = pos.coords.latitude;
            document.getElementById('inputLng').value = pos.coords.longitude;
            document.getElementById('inputAcc').value = pos.coords.accuracy;
            
            document.getElementById('btnSubmit').disabled = false;
            
            Swal.fire('Berhasil!', 'Lokasi berhasil didapatkan dengan akurasi ' + pos.coords.accuracy.toFixed(2) + ' meter.', 'success');
            
            btn.innerHTML = '<i class="mdi mdi-check btn-icon-prepend"></i> Lokasi Tersimpan';
            btn.classList.replace('btn-warning', 'btn-success');
        } catch (error) {
            console.error(error);
            Swal.fire('Gagal!', 'Gagal mendapatkan lokasi: ' + error.message, 'error');
            btn.innerHTML = '<i class="mdi mdi-refresh btn-icon-prepend"></i> Coba Lagi';
            btn.disabled = false;
        }
    }
</script>
@endpush
