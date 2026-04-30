@extends('layouts.app')

@section('content')
<style>
    .customer-card {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.03) !important;
        background: #ffffff;
    }
    .form-control-custom {
        border-radius: 12px !important;
        border: 1.5px solid #f0f0f0 !important;
        padding: 12px 15px !important;
        transition: all 0.3s;
    }
    .form-control-custom:focus {
        border-color: #00d2ff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 210, 255, 0.1) !important;
    }
    #camera-preview {
        width: 100%;
        max-width: 400px;
        border-radius: 15px;
        background: #000;
        transform: scaleX(-1);
    }
    #photo-preview {
        width: 100%;
        max-width: 400px;
        border-radius: 15px;
        display: none;
        border: 4px solid #00d2ff;
        box-shadow: 0 10px 30px rgba(0, 210, 255, 0.2);
    }
    .camera-container {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 25px;
        text-align: center;
    }
    .info-badge {
        background: rgba(0, 210, 255, 0.1);
        color: #00d2ff;
        padding: 5px 15px;
        border-radius: 10px;
        font-weight: bold;
        font-size: 11px;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-info text-white me-2 shadow-sm">
                <i class="mdi mdi-image-area"></i>
            </span> Tambah Customer (FILE)
        </h3>
    </div>
    <div class="header-right">
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-icon-text fw-bold rounded-pill px-4">
            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card customer-card">
            <div class="card-body p-4">
                <form action="{{ route('customer.store2') }}" method="POST" id="customerForm">
                    @csrf
                    <div class="row">
                        <!-- Form Inputs -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold text-info mb-0">Informasi Customer</h5>
                                <span class="info-badge">METODE: STORAGE FILE</span>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="small fw-bold text-muted">NAMA LENGKAP <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control form-control-custom" placeholder="Masukkan nama customer" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="small fw-bold text-muted">EMAIL</label>
                                <input type="email" name="email" class="form-control form-control-custom" placeholder="contoh@mail.com">
                            </div>
                            <div class="form-group mb-4">
                                <label class="small fw-bold text-muted">NOMOR TELEPON</label>
                                <input type="text" name="telepon" class="form-control form-control-custom" placeholder="08xxxxxx">
                            </div>
                            
                            <div class="alert alert-info border-0 rounded-3 small p-3">
                                <i class="mdi mdi-information-outline me-2"></i>
                                Foto akan disimpan sebagai file fisik di server (folder <code>storage/customers/</code>). Metode ini lebih efisien untuk performa database.
                            </div>
                            
                            <input type="hidden" name="foto_base64" id="foto_base64">
                            
                            <button type="submit" id="btnSubmit" class="btn btn-gradient-info btn-lg w-100 fw-bold shadow-sm rounded-pill py-3 mt-3">
                                <i class="mdi mdi-cloud-upload me-2"></i> SIMPAN & UPLOAD FOTO
                            </button>
                        </div>

                        <!-- Camera Section -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-4 text-info">Live Capture</h5>
                            <div class="camera-container shadow-sm border">
                                <div id="camera-box" class="mb-3">
                                    <video id="camera-preview" autoplay playsinline></video>
                                    <img id="photo-preview" src="" class="animate__animated animate__zoomIn">
                                    <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
                                </div>
                                
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" id="btn-start" class="btn btn-dark rounded-pill px-3">
                                        <i class="mdi mdi-camera-switch me-1"></i> Buka Kamera
                                    </button>
                                    <button type="button" id="btn-capture" class="btn btn-gradient-info rounded-pill px-3" style="display:none;">
                                        <i class="mdi mdi-camera-iris me-1"></i> Ambil Foto
                                    </button>
                                    <button type="button" id="btn-retake" class="btn btn-outline-danger rounded-pill px-3" style="display:none;">
                                        <i class="mdi mdi-refresh me-1"></i> Ulangi
                                    </button>
                                </div>
                                <p class="small text-muted mt-3 mb-0 fw-bold" id="camera-status">Status: Kamera Offline</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')
<script>
    const video = document.getElementById('camera-preview');
    const canvas = document.getElementById('canvas');
    const photo = document.getElementById('photo-preview');
    const btnStart = document.getElementById('btn-start');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const btnSubmit = document.getElementById('btnSubmit');
    const inputBase64 = document.getElementById('foto_base64');
    const statusText = document.getElementById('camera-status');
    const form = document.getElementById('customerForm');

    let stream = null;

    btnStart.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "user" }, 
                audio: false 
            });
            video.srcObject = stream;
            
            btnStart.style.display = 'none';
            btnCapture.style.display = 'inline-block';
            statusText.innerText = 'Status: Kamera Online';
            statusText.classList.replace('text-muted', 'text-success');
        } catch (err) {
            Swal.fire('Error', 'Gagal mengakses kamera.', 'error');
        }
    });

    btnCapture.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const data = canvas.toDataURL('image/jpeg');
        photo.setAttribute('src', data);
        inputBase64.value = data;

        video.style.display = 'none';
        photo.style.display = 'inline-block';
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'inline-block';
        statusText.innerText = 'Status: Foto Terpilih';
        
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });

    btnRetake.addEventListener('click', () => {
        video.style.display = 'inline-block';
        photo.style.display = 'none';
        btnRetake.style.display = 'none';
        btnCapture.style.display = 'inline-block';
        inputBase64.value = '';
        btnStart.click();
    });

    form.addEventListener('submit', (e) => {
        if (!inputBase64.value) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Ambil foto terlebih dahulu!', 'warning');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses Upload...';
        }
    });
</script>
@endpush
