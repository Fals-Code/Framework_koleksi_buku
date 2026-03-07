@extends('layouts.app')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s ease-out; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-profile {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        border-radius: 30px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05) !important;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
        padding: 8px;
        background: linear-gradient(45deg, #da8cff, #9a55ff);
        border-radius: 50%;
        box-shadow: 0 10px 30px rgba(182, 109, 255, 0.4);
    }

    .form-control-modern {
        border-radius: 15px !important;
        padding: 12px 20px !important;
        border: 2px solid #f0f0f0 !important;
        transition: all 0.3s ease;
    }
    .form-control-modern:focus {
        border-color: #b66dff !important;
        box-shadow: 0 0 15px rgba(182, 109, 255, 0.1) !important;
        transform: translateY(-2px);
    }

    .btn-loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin: -10px 0 0 -10px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .status-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(75, 222, 151, 0.2);
        color: #2ecc71;
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 0.7rem;
        letter-spacing: 1px;
    }
</style>

<div class="page-header flex-wrap fade-in-up">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow">
            <i class="mdi mdi-shield-check"></i>
        </span> 
        <span class="fw-bold">Security Center</span>
    </h3>
</div>

<div class="row fade-in-up" style="animation-delay: 0.2s;">
    <div class="col-lg-4 mb-4">
        <div class="card glass-profile text-center py-5 position-relative overflow-hidden">
            <div class="status-badge"><i class="mdi mdi-circle me-1"></i> Verified</div>
            <div style="position:absolute; top:-50px; right:-50px; width:150px; height:150px; background:rgba(182, 109, 255, 0.1); border-radius:50%"></div>

            <div class="card-body">
                <div class="avatar-wrapper mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=fff&color=b66dff&size=150&bold=true" 
                         class="rounded-circle shadow-lg" alt="profile" style="width: 130px; border: 4px solid white;">
                </div>
                
                <h3 class="fw-bold mb-1 text-dark">{{ $user->name }}</h3>
                <p class="text-muted mb-4 small">{{ $user->email }}</p>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <div class="p-2 bg-white rounded-4 flex-fill shadow-sm border">
                        <small class="text-muted d-block" style="font-size: 10px;">ROLE</small>
                        <span class="fw-bold small text-primary">Administrator</span>
                    </div>
                    <div class="p-2 bg-white rounded-4 flex-fill shadow-sm border">
                        <small class="text-muted d-block" style="font-size: 10px;">SINCE</small>
                        <span class="fw-bold small text-dark">{{ $user->created_at->format('Y') }}</span>
                    </div>
                </div>

                <div class="alert alert-secondary border-0 small text-start mb-0 shadow-sm" style="border-radius: 15px;">
                    <i class="mdi mdi-clock-outline me-1"></i> Sesi aktif: {{ now()->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card glass-profile border-0 shadow-lg">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-5">
                    <div class="bg-gradient-info p-2 rounded-3 me-3 shadow-sm">
                        <i class="mdi mdi-account-cog text-white mdi-24px"></i>
                    </div>
                    <h4 class="card-title mb-0 fw-bold">Pengaturan Identitas</h4>
                </div>

                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" onsubmit="return handleProfileSubmit(this)">
                    @csrf
                    @method('PUT')

                    @if(isset($needsPassword) && $needsPassword)
                    <div class="alert text-white border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 15px; background: linear-gradient(to right, #ffbf96, #fe7096);">
                        <i class="mdi mdi-google-plus mdi-36px me-3"></i>
                        <div>
                            <p class="mb-0 fw-bold">Akun Media Sosial Terdeteksi</p>
                            <small>Keamanan: Harap buat password untuk akses login manual.</small>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="small fw-bold text-muted ms-2 mb-2">NAMA LENGKAP</label>
                            <input type="text" name="name" class="form-control form-control-modern @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="small fw-bold text-muted ms-2 mb-2">ALAMAT EMAIL</label>
                            <input type="email" name="email" class="form-control form-control-modern @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <hr class="my-5 opacity-25">

                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-gradient-warning p-2 rounded-3 me-3 shadow-sm text-white">
                            <i class="mdi mdi-lock-reset mdi-18px"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Keamanan Password</h5>
                    </div>

                    <div class="row">
                        @if(!(isset($needsPassword) && $needsPassword))
                        <div class="col-md-12 mb-4">
                            <label class="small fw-bold text-muted ms-2 mb-2">PASSWORD SAAT INI</label>
                            <div class="input-group">
                                <input type="password" name="current_password" class="form-control form-control-modern @error('current_password') is-invalid @enderror" placeholder="••••••••">
                                <button class="btn btn-outline-light text-muted border-0" type="button" onclick="togglePass(this)"><i class="mdi mdi-eye"></i></button>
                            </div>
                            @error('current_password') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                        @endif

                        <div class="col-md-6 mb-4">
                            <label class="small fw-bold text-muted ms-2 mb-2">PASSWORD BARU</label>
                            <input type="password" name="password" class="form-control form-control-modern @error('password') is-invalid @enderror" placeholder="Min. 8 Karakter">
                            @error('password') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="small fw-bold text-muted ms-2 mb-2">KONFIRMASI PASSWORD</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-modern" placeholder="Ulangi Password">
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" id="btnUpdateProfile" class="btn btn-gradient-primary btn-lg rounded-pill px-5 fw-bold shadow">
                            <i class="mdi mdi-check-all me-2"></i> UPDATE PROFIL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePass(btn) {
        const input = btn.closest('.input-group').querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('mdi-eye', 'mdi-eye-off');
        } else {
            input.type = "password";
            icon.classList.replace('mdi-eye-off', 'mdi-eye');
        }
    }

    function handleProfileSubmit(form) {
        const btn = document.getElementById('btnUpdateProfile');
        btn.classList.add('btn-loading');
        return true;
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#ffffff',
            iconColor: '#b66dff',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0'
            }
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Waduh...',
            text: 'Ada kesalahan pada input data kamu. Cek kembali ya!',
            confirmButtonColor: '#b66dff',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0',
                confirmButton: 'rounded-pill px-4'
            }
        });
    @endif

    function handleProfileSubmit(form) {
        const btn = document.getElementById('btnUpdateProfile');
        
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Pastikan data identitas sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#b66dff',
            cancelButtonColor: '#fe7096',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0',
                confirmButton: 'rounded-pill px-4',
                cancelButton: 'rounded-pill px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                btn.classList.add('btn-loading');
                form.submit();
            }
        });
        
        return false;
    }
</script>
@endsection