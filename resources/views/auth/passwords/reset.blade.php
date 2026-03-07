@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card glass-profile border-0 shadow-lg p-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-5">
                        <div class="bg-gradient-primary p-2 rounded-3 me-3">
                            <i class="mdi mdi-key-variant text-white"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">{{ __('New Password Setup') }}</h4>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="small fw-bold text-muted ms-2">{{ __('EMAIL ADDRESS') }}</label>
                            <input id="email" type="email" class="form-control form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required readonly>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted ms-2">{{ __('NEW PASSWORD') }}</label>
                                <input id="password" type="password" class="form-control form-control-modern @error('password') is-invalid @enderror" name="password" required placeholder="Min. 8 Karakter">
                                @error('password')
                                    <span class="invalid-feedback ms-2" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted ms-2">{{ __('REPEAT PASSWORD') }}</label>
                                <input id="password-confirm" type="password" class="form-control form-control-modern" name="password_confirmation" required placeholder="Konfirmasi">
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill px-5 fw-bold shadow">
                                <i class="mdi mdi-lock-open-outline me-2"></i> {{ __('RESET & LOGIN') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection