@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        --info-gradient: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    .form-box {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .form-control-premium {
        border-radius: 15px !important;
        border: 1.5px solid #eee !important;
        padding: 12px 20px !important;
        transition: all 0.3s;
        background: #fdfdfd;
    }

    .form-control-premium:focus {
        border-color: #b66dff !important;
        box-shadow: 0 0 0 4px rgba(182, 109, 255, 0.1) !important;
        background: #fff;
    }

    .photo-preview-container {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 30px;
    }

    .photo-box {
        width: 100%;
        height: 100%;
        border: 3px dashed #ddd;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
        border-radius: 25px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .photo-box.has-image {
        border-style: solid;
        border-color: #b66dff;
    }

    .photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-capture-trigger {
        position: absolute;
        bottom: -10px;
        right: -10px;
        width: 50px;
        height: 50px;
        border-radius: 15px;
        background: var(--primary-gradient);
        color: white;
        border: 4px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(106, 17, 203, 0.3);
        transition: all 0.3s;
    }

    .btn-capture-trigger:hover {
        transform: scale(1.1);
    }

    .btn-gradient-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 15px 30px;
        border-radius: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 20px rgba(106, 17, 203, 0.2);
        transition: all 0.3s;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(106, 17, 203, 0.3);
        color: white;
    }

    .modal-content-premium {
        border-radius: 30px;
        border: none;
        overflow: hidden;
    }

    .camera-preview-box {
        width: 100%;
        background: #000;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    #video { width: 100%; height: auto; display: block; }
    #snapshot { width: 100%; height: auto; display: none; }

    .section-title {
        font-weight: 800;
        font-size: 0.8rem;
        color: #888;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
        display: block;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-account-plus"></i>
            </span> Tambah Customer Baru
        </h3>
    </div>
    <div class="header-right">
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
            <i class="mdi mdi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="form-box">
            <form action="{{ route('customer.store1') }}" method="POST" id="customerForm">
                @csrf
                
                <div class="row">
                    <!-- Photo Column -->
                    <div class="col-md-4 text-center">
                        <span class="section-title">FOTO CUSTOMER</span>
                        <div class="photo-preview-container">
                            <div class="photo-box" id="main-photo-preview">
                                <i class="mdi mdi-camera-plus-outline text-muted mb-2" style="font-size: 40px;"></i>
                                <span class="text-muted small fw-bold">BELUM ADA FOTO</span>
                            </div>
                            <div class="btn-capture-trigger" data-bs-toggle="modal" data-bs-target="#cameraModal" title="Ambil Foto">
                                <i class="mdi mdi-camera mdi-24px"></i>
                            </div>
                        </div>
                        <input type="hidden" name="foto_base64" id="foto_base64">
                        <p class="small text-muted mb-4 px-3">Klik tombol kamera biru di bawah untuk mengambil foto customer secara langsung.</p>
                    </div>

                    <!-- Input Column -->
                    <div class="col-md-8">
                        <span class="section-title">IDENTITAS & ALAMAT</span>
                        
                        <div class="row g-3">
                            <div class="col-12 mb-2">
                                <div class="form-group mb-0">
                                    <label class="small fw-bold text-dark mb-1">NAMA LENGKAP <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 border-radius-15" style="border-radius: 15px 0 0 15px !important;"><i class="mdi mdi-account text-primary"></i></span>
                                        <input type="text" name="nama" class="form-control form-control-premium border-start-0" style="border-radius: 0 15px 15px 0 !important;" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small fw-bold text-dark mb-1">EMAIL</label>
                                <input type="email" name="email" class="form-control form-control-premium" placeholder="nama@email.com">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small fw-bold text-dark mb-1">TELEPON</label>
                                <input type="text" name="telepon" class="form-control form-control-premium" placeholder="08XXXXXXXXX">
                            </div>

                            <div class="col-12 mb-2">
                                <label class="small fw-bold text-dark mb-1">ALAMAT LENGKAP</label>
                                <textarea name="alamat" class="form-control form-control-premium" rows="2" placeholder="Jalan, No Rumah, RT/RW"></textarea>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small fw-bold text-dark mb-1">PROVINSI</label>
                                <input type="text" name="provinsi" class="form-control form-control-premium" placeholder="Provinsi">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small fw-bold text-dark mb-1">KOTA / KABUPATEN</label>
                                <input type="text" name="kota" class="form-control form-control-premium" placeholder="Kota/Kabupaten">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small fw-bold text-dark mb-1">KECAMATAN</label>
                                <input type="text" name="kecamatan" class="form-control form-control-premium" placeholder="Kecamatan">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="small fw-bold text-dark mb-1">KODEPOS - KELURAHAN</label>
                                <input type="text" name="kodepos" class="form-control form-control-premium" placeholder="Kodepos - Kelurahan">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gradient-primary btn-lg w-100">
                                <i class="mdi mdi-check-circle-outline me-2"></i> SIMPAN DATA CUSTOMER
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ambil Foto Premium -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-premium shadow-lg">
            <div class="modal-header bg-gradient-primary text-white p-4">
                <h5 class="modal-title fw-bold">
                    <i class="mdi mdi-camera-plus me-2"></i> Capture Customer Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="camera-preview-box">
                            <video id="video" autoplay playsinline></video>
                        </div>
                        <div class="mt-3">
                            <label class="small fw-bold text-muted d-block mb-2">PILIH INPUT KAMERA</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="mdi mdi-webcam text-primary"></i></span>
                                <select id="cameraSelect" class="form-select border-start-0 rounded-end-3">
                                    <option value="">Deteksi Otomatis...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="camera-preview-box">
                            <img id="snapshot" src="">
                            <canvas id="canvas" style="display:none;" width="640" height="480"></canvas>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-dark btn-lg rounded-pill w-100 fw-bold py-3 mb-2" id="btn-capture">
                                <i class="mdi mdi-camera-iris me-2"></i> AMBIL FOTO
                            </button>
                            <p class="x-small text-muted italic">Pratinjau hasil foto akan muncul di atas.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 justify-content-center">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">BATAL</button>
                <button type="button" class="btn btn-gradient-primary rounded-pill px-5 py-3" id="btn-save-photo" disabled>
                    <i class="mdi mdi-image-check me-2"></i> GUNAKAN FOTO INI
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const snapshot = document.getElementById('snapshot');
    const cameraSelect = document.getElementById('cameraSelect');
    const btnCapture = document.getElementById('btn-capture');
    const btnSavePhoto = document.getElementById('btn-save-photo');
    const inputBase64 = document.getElementById('foto_base64');
    const mainPreview = document.getElementById('main-photo-preview');

    let currentStream = null;

    async function getCameras() {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        
        cameraSelect.innerHTML = '<option value="">Pilih Kamera...</option>';
        videoDevices.forEach(device => {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.text = device.label || `Camera ${cameraSelect.length + 1}`;
            cameraSelect.appendChild(option);
        });
    }

    async function startStream(deviceId = null) {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        const constraints = {
            video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: "user" }
        };

        try {
            currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = currentStream;
        } catch (error) {
            console.error('Error accessing camera:', error);
            Swal.fire('Kamera Error', 'Gagal mengakses kamera: ' + error.message, 'error');
        }
    }

    const cameraModal = document.getElementById('cameraModal');
    cameraModal.addEventListener('shown.bs.modal', async () => {
        await getCameras();
        await startStream();
    });

    cameraModal.addEventListener('hidden.bs.modal', () => {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    });

    cameraSelect.addEventListener('change', () => {
        startStream(cameraSelect.value);
    });

    btnCapture.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const data = canvas.toDataURL('image/jpeg');
        snapshot.src = data;
        snapshot.style.display = 'block';
        video.style.display = 'none';
        btnSavePhoto.disabled = false;
        
        // Vibration effect for feedback
        if (window.navigator.vibrate) window.navigator.vibrate(50);
    });

    btnSavePhoto.addEventListener('click', () => {
        const data = snapshot.src;
        inputBase64.value = data;
        
        mainPreview.innerHTML = `<img src="${data}" alt="Customer Photo">`;
        mainPreview.classList.add('has-image');
        
        // Safety close modal
        const modalElement = document.getElementById('cameraModal');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }

        // Force cleanup to prevent frozen UI (common Bootstrap bug when hiding via JS)
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }, 400);
        
        Swal.fire({
            icon: 'success',
            title: 'Foto Siap',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    });

    // Reset view when retaking in same session
    btnCapture.addEventListener('dblclick', () => {
        snapshot.style.display = 'none';
        video.style.display = 'block';
        btnSavePhoto.disabled = true;
    });
</script>
@endpush
