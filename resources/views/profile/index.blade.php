@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account"></i>
        </span> Profil Saya
    </h3>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Akun</h4>
                
                @if($needsPassword)
                <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    <strong>Perhatian!</strong> Anda login menggunakan Google. Silakan buat password untuk keamanan akun Anda.
                </div>
                @endif

                <form class="forms-sample" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <hr class="my-4">
                    <h4 class="card-title text-primary">@if($needsPassword) Buat Password @else Ganti Password @endif</h4>
                    <p class="text-muted small">Kosongkan jika tidak ingin mengubah password.</p>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn btn-gradient-primary me-2 rounded-pill px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="card-body">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=b66dff&color=fff&size=128" class="rounded-circle img-fluid mb-3 shadow" alt="profile">
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <div class="badge badge-gradient-success">Status: Aktif</div>
            </div>
        </div>
    </div>
</div>
@endsection