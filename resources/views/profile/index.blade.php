@extends('layouts.app')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s ease-out; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-profile {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 30px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05) !important;
    }
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        padding: 10px;
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
        transform: scale(1.01);
    }

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
        text-transform: uppercase;
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
                <p class="text-muted mb-4">{{ $user->email }}</p>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <div class="p-3 bg-light rounded-4 flex-fill">
                        <small class="text-muted d-block">Role</small>
                        <span class="fw-bold">Administrator</span>
                    </div>
                    <div class="p-3 bg-light rounded-4 flex-fill">
                        <small class="text-muted d-block">Since</small>
                        <span class="fw-bold">{{ $user->created_at->format('Y') }}</span>
                    </div>
                </div>

                <div class="alert alert-secondary border-0 small text-start mb-0" style="border-radius: 20px;">
                    <i class="mdi mdi-clock-outline me-1"></i> Login terakhir: {{ now()->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card glass-profile border-0">
            <div class="card-body p-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-gradient-info p-2 rounded-3 me-3">
                        <i class="mdi mdi-settings text-white"></i>
                    </div>
                    <h4 class="card-title mb-0 fw-bold">Pengaturan Identitas</h4>
                </div>

                <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if($needsPassword)
                    <div class="alert alert-gradient-warning text-white border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 15px; background: linear-gradient(to right, #ffbf96, #fe7096);">
                        <i class="mdi mdi-google mdi-36px me-3"></i>
                        <div>
                            <p class="mb-0 fw-bold">Akun Google Terdeteksi</p>
                            <small>Keamanan ekstra: Mohon buat password untuk akses manual.</small>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted ms-2">NAMA LENGKAP</label>
                            <input type="text" name="name" class="form-control form-control-modern @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                            @error('name') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted ms-2">ALAMAT EMAIL</label>
                            <input type="email" name="email" class="form-control form-control-modern @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                            @error('email') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <hr class="my-5 opacity-25">

                    <h5 class="fw-bold text-primary mb-4"><i class="mdi mdi-lock-reset me-2"></i>Keamanan Password</h5>

                    <div class="row">
                        @if(!$needsPassword)
                        <div class="col-md-12 mb-4">
                            <label class="small fw-bold text-muted ms-2">PASSWORD SAAT INI</label>
                            <input type="password" name="current_password" class="form-control form-control-modern @error('current_password') is-invalid @enderror" placeholder="••••••••">
                            @error('current_password') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted ms-2">PASSWORD BARU</label>
                            <input type="password" name="password" class="form-control form-control-modern @error('password') is-invalid @enderror" placeholder="Min. 8 Karakter">
                            @error('password') <small class="text-danger ms-2">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted ms-2">KONFIRMASI PASSWORD</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-modern" placeholder="Ulangi Password">
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill px-5 fw-bold shadow">
                            <i class="mdi mdi-check-all me-2"></i> UPDATE PROFIL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection