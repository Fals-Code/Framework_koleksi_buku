<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koleksi Buku - OTP Verification</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    
    <style>
      .auth.auth-bg-1 { background: linear-gradient(135deg, #7117ea 0%, #ea6060 100%); }
      
      .auth-form-light {
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
      }

      .btn-gradient-primary {
        transition: all 0.3s ease;
        border-radius: 10px;
        font-weight: bold;
        border: none;
      }

      .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(182, 109, 255, 0.4);
      }

      .otp-input-custom {
        letter-spacing: 12px;
        font-size: 28px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        border-radius: 12px !important;
        border: 2px solid #e8eff9 !important;
        background: #fdfdff !important;
        color: #7117ea !important;
        transition: all 0.3s ease;
      }

      .otp-input-custom:focus {
        border-color: #7117ea !important;
        box-shadow: 0 0 10px rgba(113, 23, 234, 0.1) !important;
        background: #fff !important;
      }

      .brand-logo img { width: 140px; }

      .mdi-lock-reset {
        font-size: 60px;
        background: -webkit-linear-gradient(#7117ea, #ea6060);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      #otpLoader {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none; align-items: center; justify-content: center;
        z-index: 9999; backdrop-filter: blur(4px);
      }
    </style>
  </head>
  <body>
    <div id="otpLoader">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-bold text-dark">Verifying Code...</p>
        </div>
    </div>

    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo mb-4">
                  <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                </div>
                
                <div class="mb-3">
                  <i class="mdi mdi-lock-reset"></i>
                </div>
                
                <h3 class="text-dark font-weight-bold">OTP Verification</h3>
                <p class="font-weight-light text-muted">Please enter the 6-character code sent to your email address.</p>
                
                <form class="pt-4" id="otpForm" method="POST" action="{{ route('otp.verify') }}">
                  @csrf
                  
                  <div class="form-group mb-4">
                    <input type="text" 
                           class="form-control form-control-lg text-center otp-input-custom @if(session('error')) is-invalid @endif" 
                           id="otp_input" 
                           name="otp_input"
                           placeholder="••••••"
                           maxlength="6"
                           required
                           autofocus
                           autocomplete="one-time-code">
                    
                    @if(session('error'))
                        <div class="invalid-feedback mt-2" role="alert">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif
                  </div>
                  
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" id="btnVerify" class="btn btn-gradient-primary btn-lg auth-form-btn text-white">
                      <i class="mdi mdi-shield-check me-2"></i> VERIFY & PROCEED
                    </button>
                  </div>
                  
                  <div class="text-center mt-4 font-weight-light text-muted"> 
                    Didn't receive the code? <br>
                    <a href="{{ route('google.login') }}" id="btnResend" class="text-primary font-weight-bold" style="text-decoration: none;">Resend or Change Account</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const otpForm = document.getElementById('otpForm');
        const loader = document.getElementById('otpLoader');
        const btnVerify = document.getElementById('btnVerify');
        const btnResend = document.getElementById('btnResend');

        otpForm.addEventListener('submit', function() {
            loader.style.display = 'flex';
            btnVerify.disabled = true;
            btnVerify.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> VERIFYING...`;
        });

        btnResend.addEventListener('click', function() {
            loader.style.display = 'flex';
            this.style.pointerEvents = 'none';
            this.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Redirecting...`;
        });
      });
    </script>
  </body>
</html>