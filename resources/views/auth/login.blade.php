<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koleksi Buku - Login</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            color: #111827;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #7117ea 0%, #ea6060 100%);
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 18px 42px rgba(17, 24, 39, 0.18);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-logo img {
            width: 150px;
            max-width: 70%;
            height: auto;
        }

        .auth-title {
            margin: 0 0 8px;
            color: #111827;
            font-size: 24px;
            font-weight: 800;
        }

        .auth-subtitle {
            margin: 0 0 24px;
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 13px;
            font-weight: 800;
        }

        .form-control {
            display: block;
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border: 1px solid #d9e1ec;
            border-radius: 8px;
            color: #111827;
            font-size: 14px;
            outline: none;
            background: #ffffff;
        }

        .form-control:focus {
            border-color: #b66dff;
            box-shadow: 0 0 0 3px rgba(182, 109, 255, 0.16);
        }

        .is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback {
            display: block;
            margin-top: 7px;
            color: #dc2626;
            font-size: 12px;
            font-weight: 700;
        }

        .btn {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:disabled,
        .btn.disabled {
            pointer-events: none;
            opacity: 0.76;
        }

        .btn-primary {
            color: #ffffff;
            background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
            box-shadow: 0 8px 18px rgba(106, 17, 203, 0.22);
        }

        .btn-google {
            margin-top: 14px;
            color: #374151;
            border: 1px solid #d9e1ec;
            background: #ffffff;
        }

        .google-mark {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            background: #4285f4;
            font-size: 13px;
            font-weight: 900;
        }

        .divider {
            margin: 18px 0 0;
            color: #6b7280;
            text-align: center;
            font-size: 13px;
        }

        .register-text {
            margin-top: 24px;
            color: #6b7280;
            text-align: center;
            font-size: 14px;
        }

        .register-text a {
            color: #6a11cb;
            font-weight: 800;
            text-decoration: none;
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.45;
        }

        .alert-danger {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .alert-info {
            color: #1e3a8a;
            background: #dbeafe;
            border: 1px solid #bfdbfe;
        }

        .loader {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.82);
        }

        .loader-content {
            color: #111827;
            text-align: center;
            font-weight: 800;
        }

        .spinner {
            width: 42px;
            height: 42px;
            display: block;
            margin: 0 auto 12px;
            border: 4px solid rgba(106, 17, 203, 0.2);
            border-top-color: #6a11cb;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        .spinner-small {
            width: 14px;
            height: 14px;
            display: inline-block;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        .btn-google .spinner-small {
            border-color: rgba(66, 133, 244, 0.22);
            border-top-color: #4285f4;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 22px;
            }
        }
    </style>
</head>
<body>
    <div id="loginLoader" class="loader" aria-live="polite" aria-label="Memproses">
        <div class="loader-content">
            <span class="spinner" aria-hidden="true"></span>
            Mohon tunggu...
        </div>
    </div>

    <main class="auth-page">
        <section class="auth-card" aria-label="Form login">
            <div class="brand-logo">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Koleksi Buku">
            </div>

            <h1 class="auth-title">Welcome back!</h1>
            <p class="auth-subtitle">Please sign in to continue.</p>

            @if(session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">Terdapat kesalahan pada input Anda.</div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    @error('email')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                </div>

                <button type="submit" id="btnLogin" class="btn btn-primary">
                    SIGN IN
                </button>

                <div class="divider">Or login with</div>

                <a href="{{ route('google.login') }}" id="btnGoogle" class="btn btn-google">
                    <span class="google-mark" aria-hidden="true">G</span>
                    Google Account
                </a>

                <div class="register-text">
                    Don't have an account?
                    <a href="{{ route('register') }}" id="btnToRegister">Create</a>
                </div>
            </form>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const loader = document.getElementById('loginLoader');
            const btnLogin = document.getElementById('btnLogin');
            const btnGoogle = document.getElementById('btnGoogle');
            const btnToRegister = document.getElementById('btnToRegister');

            function showLoader() {
                loader.style.display = 'flex';
                window.setTimeout(function () {
                    loader.style.display = 'none';
                }, 8000);
            }

            loginForm.addEventListener('submit', function () {
                if (!loginForm.checkValidity()) {
                    return;
                }

                showLoader();
                btnLogin.disabled = true;
                btnLogin.innerHTML = '<span class="spinner-small" aria-hidden="true"></span> SIGNING IN...';
            });

            btnGoogle.addEventListener('click', function () {
                showLoader();
                btnGoogle.classList.add('disabled');
                btnGoogle.innerHTML = '<span class="spinner-small" aria-hidden="true"></span> Connecting...';
            });

            btnToRegister.addEventListener('click', function () {
                showLoader();
                btnToRegister.style.pointerEvents = 'none';
                btnToRegister.textContent = 'Preparing...';
            });
        });
    </script>
</body>
</html>
