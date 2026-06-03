@extends('layouts.app')

@push('style-page')
<style>
    #reader {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border: 2px solid #b66dff;
        border-radius: 10px;
        overflow: hidden;
    }
    #reader__scan_region {
        background: white;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">Scan Kunjungan Toko</h4>
                <p class="card-description">Arahkan kamera ke barcode toko, kami akan mencatat lokasi Anda secara otomatis.</p>
                
                <div id="reader"></div>
                
                <div id="loadingInfo" class="mt-4" style="display: none;">
                    <h5 class="text-primary"><i class="mdi mdi-loading mdi-spin"></i> Mendapatkan lokasi GPS yang akurat...</h5>
                    <p class="text-muted">Jangan tutup halaman ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // Lampiran 1: Fungsi JS untuk ambil lokasi
    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) { 
        return new Promise((resolve, reject) => { 
            let bestResult = null; 
            const startTime = Date.now(); 
            const watchId = navigator.geolocation.watchPosition( 
                (position) => { 
                    const acc = position.coords.accuracy; 
                    if (!bestResult || acc < bestResult.coords.accuracy) { 
                        bestResult = position; 
                    } 
                    if (acc <= targetAccuracy) { 
                        navigator.geolocation.clearWatch(watchId); 
                        resolve(bestResult); 
                    } 
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

    let isScanning = true;
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);

    async function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        
        isScanning = false;
        html5QrcodeScanner.pause();
        
        document.getElementById('loadingInfo').style.display = 'block';
        
        try {
            // Ambil lokasi GPS sales
            const pos = await getAccuratePosition(50, 20000); 
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const acc = pos.coords.accuracy;
            
            // Kirim ke server
            const response = await fetch("{{ route('kunjungan-toko.proses') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    barcode: decodedText,
                    latitude: lat,
                    longitude: lng,
                    accuracy: acc
                })
            });
            
            const result = await response.json();
            document.getElementById('loadingInfo').style.display = 'none';
            
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'DITERIMA ✓',
                    html: `<b>Toko:</b> ${result.toko.nama_toko}<br><b>Jarak Aktual:</b> ${result.jarak_aktual}m<br><b>Toleransi (Threshold Efektif):</b> ${result.threshold_efektif}m<br><br><i>Kunjungan Anda tercatat!</i>`,
                    confirmButtonText: 'Lanjut Scan Lainnya'
                }).then(() => {
                    isScanning = true;
                    html5QrcodeScanner.resume();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'DITOLAK ✗',
                    html: `<b>Toko:</b> ${result.toko ? result.toko.nama_toko : 'Tidak Ditemukan'}<br><b>Jarak Aktual:</b> ${result.jarak_aktual || '-'}m<br><b>Toleransi (Threshold Efektif):</b> ${result.threshold_efektif || '-'}m<br><br><i>Posisi Anda terlalu jauh dari lokasi toko.</i>`,
                    confirmButtonText: 'Coba Lagi'
                }).then(() => {
                    isScanning = true;
                    html5QrcodeScanner.resume();
                });
            }
            
        } catch (error) {
            document.getElementById('loadingInfo').style.display = 'none';
            console.error(error);
            Swal.fire('Gagal Mendapatkan Lokasi', error.message, 'warning').then(() => {
                isScanning = true;
                html5QrcodeScanner.resume();
            });
        }
    }

    function onScanFailure(error) {
        // Abaikan error saat tidak ada QR di depan layar
    }

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush
