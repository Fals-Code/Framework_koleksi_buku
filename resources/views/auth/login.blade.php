<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koleksi Buku - Login</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
      .auth.auth-bg-1 { background: linear-gradient(135deg, #7117ea 0%, #ea6060 100%); }
      .auth-form-light { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
      .btn-gradient-primary { transition: all 0.3s ease; border-radius: 8px; }
      .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(182, 109, 255, 0.4); }
      .btn-google { background: #ffffff !important; color: #555 !important; border: 1px solid #ddd !important; transition: all 0.3s ease; font-weight: 500; }
      .btn-google:hover { background: #f8f9fa !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transform: translateY(-1px); }
      .form-control-lg { border-radius: 10px !important; font-size: 0.9rem; border: 1px solid #e8eff9; }
      .form-control:focus { border-color: #b66dff; box-shadow: 0 0 0 0.2rem rgba(182, 109, 255, 0.1); }
      .brand-logo img { width: 150px; }
      .modal-content { border-radius: 20px; overflow: hidden; }
      .bg-gradient-primary { background: linear-gradient(to right, #da8cff, #9a55ff) !important; }
      .transition-all { transition: all 0.5s ease; }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5 shadow-lg">
                <div class="brand-logo text-center">
                  <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                </div>
                <h4 class="text-dark font-weight-bold">Welcome back!</h4>
                <h6 class="font-weight-light text-muted text-center">Please sign in to continue.</h6>
                <form class="pt-4" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Email Address</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email Anda" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Password</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan kata sandi akun Anda" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                  <div class="my-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Keep me signed in 
                      </label>
                    </div>
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                      <i class="mdi mdi-login me-2"></i>SIGN IN
                    </button>
                  </div>
                  <div class="text-center mt-3 text-muted small">— Or login with —</div>
                  <div class="mt-3 d-grid gap-2">
                    <a href="{{ route('google.login') }}" class="btn btn-block btn-google auth-form-btn">
                      <img src="https://authjs.dev/img/providers/google.svg" width="18" class="me-2" alt="Google"> Sign in with Google
                    </a>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> 
                    Don't have an account? <a href="{{ route('register') }}" class="text-primary font-weight-bold">Create one</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if(session()->has('notifications'))
      @foreach(session('notifications') as $index => $n)
        <div class="modal fade" id="notifModal{{ $index }}" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
              <div id="header-{{ $index }}" class="modal-header bg-gradient-primary text-white transition-all">
                <h5 class="modal-title d-flex align-items-center">
                  <span id="badge-unread-{{ $index }}" class="badge bg-warning text-dark me-2" style="font-size: 10px;">BELUM DIBACA</span>
                  <span id="badge-read-{{ $index }}" class="badge bg-light text-dark me-2 d-none" style="font-size: 10px;">SUDAH DIBACA</span>
                  Sistem Notifikasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center p-5">
                <div id="icon-box-{{ $index }}" class="mb-4">
                    <i class="mdi mdi-bell-ring text-primary animated swing infinite" style="font-size: 60px;"></i>
                </div>
                <h3 class="font-weight-bold text-dark">{{ $n['title'] }}</h3>
                <p class="text-muted mt-3" style="font-size: 1.1rem;">{{ $n['message'] }}</p>
                <div class="mt-4 pt-3 border-top">
                  <small class="text-secondary"><i class="mdi mdi-clock-outline"></i> Tercatat pada: {{ $n['time'] }} WIB</small>
                </div>
              </div>
              <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-gradient-primary btn-lg px-5 shadow-sm" data-bs-dismiss="modal">Mengerti</button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @endif
    
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        @if(session()->has('notifications'))
          @foreach(session('notifications') as $index => $n)
            var modalEl{{ $index }} = document.getElementById('notifModal{{ $index }}');
            var myModal{{ $index }} = new bootstrap.Modal(modalEl{{ $index }});
            myModal{{ $index }}.show();

            modalEl{{ $index }}.addEventListener('shown.bs.modal', function () {
              setTimeout(function() {
                document.getElementById('header-{{ $index }}').classList.remove('bg-gradient-primary');
                document.getElementById('header-{{ $index }}').classList.add('bg-secondary');
                document.getElementById('badge-unread-{{ $index }}').classList.add('d-none');
                document.getElementById('badge-read-{{ $index }}').classList.remove('d-none');
                document.getElementById('icon-box-{{ $index }}').innerHTML = '<i class="mdi mdi-bell-check text-muted" style="font-size: 60px;"></i>';
              }, 2000);
            });
          @endforeach
        @endif
      });
    </script>
  </body>
</html>