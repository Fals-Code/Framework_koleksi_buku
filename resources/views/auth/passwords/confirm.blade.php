@extends('layouts.app')

@section('content')
<style>
    .glass-confirm {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(15px);
        border-radius: 25px !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .form-control-modern {
        border-radius: 12px !important;
        border: 2px solid #f0f0f0 !important;
        padding: 12px 20px !important;
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card glass-confirm p-4 text-center">
                <div class="mb-4 mt-3">
                    <i class="mdi mdi-lock-question bg-gradient-danger text-white p-3 rounded-circle shadow" style="font-size: 40px;"></i>
                </div>
                
                <h4 class="fw-bold text-dark">{{ __('Confirm Password') }}</h4>
                <p class="text-muted small px-4">{{ __('Keamanan Akun: Mohon konfirmasi password Anda sebelum melanjutkan ke halaman profil.') }}</p>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4 text-start">
                            <label for="password" class="small fw-bold text-muted ms-2">{{ __('PASSWORD') }}</label>
                            <input id="password" type="password" class="form-control form-control-modern @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            @error('password')
                                <span class="invalid-feedback ms-2" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill fw-bold shadow">
                                <i class="mdi mdi-shield-check me-2"></i>{{ __('CONFIRM ACCESS') }}
                            </button>
                            
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-muted small" href="{{ route('password.request') }}">
                                    {{ __('Lupa password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection