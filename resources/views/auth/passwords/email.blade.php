@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            <div class="card glass-profile border-0 p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-gradient-info p-3 d-inline-block rounded-4 shadow-sm mb-3">
                            <i class="mdi mdi-email-send text-white mdi-36px"></i>
                        </div>
                        <h4 class="fw-bold">{{ __('Reset Password') }}</h4>
                        <p class="text-muted small">Masukkan email Anda untuk menerima link reset password.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success border-0 rounded-4" role="alert">
                            <i class="mdi mdi-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="small fw-bold text-muted ms-2">{{ __('EMAIL ADDRESS') }}</label>
                            <input id="email" type="email" class="form-control form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            @error('email')
                                <span class="invalid-feedback ms-2" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill fw-bold shadow">
                                <i class="mdi mdi-send me-2"></i>{{ __('SEND RESET LINK') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection