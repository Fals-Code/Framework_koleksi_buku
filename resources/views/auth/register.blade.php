<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koleksi Buku - Register</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
      .auth.auth-bg-1 { background: linear-gradient(135deg, #7117ea 0%, #ea6060 100%); }
      .auth-form-light { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
      .btn-gradient-primary { transition: all 0.3s ease; border-radius: 8px; border: none; }
      .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(182, 109, 255, 0.4); }
      .form-control-lg { border-radius: 10px !important; font-size: 0.9rem; border: 1px solid #e8eff9; padding: 1rem 1.5rem; }
      #registerLoader {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none; align-items: center; justify-content: center;
        z-index: 9999; backdrop-filter: blur(4px);
      }
    </style>
  </head>
  <body>
    <div id="registerLoader">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-bold text-dark">Mendaftarkan Akun...</p>
        </div>
    </div>

    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5 shadow-lg">
                <div class="brand-logo text-center">
                  <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                </div>
                <h4 class="text-dark font-weight-bold mt-3">Join us today!</h4>
                <h6 class="font-weight-light text-muted">Sign up to get started.</h6>
                
                <form class="pt-4" id="registerForm" method="POST" action="{{ route('register') }}">
                  @csrf
                  
                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Full Name</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Your Name" required autofocus>
                    @error('name')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>

                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Email Address</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required>
                    @error('email')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>

                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Password</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                    @error('password')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>

                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Confirm Password</label>
                    <input type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Repeat Password" required>
                  </div>
                  
                  <div class="mt-4 d-grid gap-2">
                    <button type="submit" id="btnRegister" class="btn btn-gradient-primary btn-lg font-weight-medium text-white">
                      <i class="mdi mdi-account-plus me-2"></i>CREATE ACCOUNT
                    </button>
                  </div>

                  <div class="text-center mt-4 font-weight-light text-muted">
                    Already have an account? 
                    <a href="{{ url('/') }}" class="text-primary fw-bold" style="text-decoration: none;">Login</a>
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
        const registerForm = document.getElementById('registerForm');
        const loader = document.getElementById('registerLoader');
        const btnRegister = document.getElementById('btnRegister');

        registerForm.addEventListener('submit', function() {
            loader.style.display = 'flex';
            btnRegister.disabled = true;
            btnRegister.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> PROCESSING...`;
        });
      });
    </script>
  </body>
</html>