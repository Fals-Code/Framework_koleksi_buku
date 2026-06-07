<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Antrian Perpustakaan | Vokasi Perpus</title>
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
            background: #f8fafc;
        }

        body {
            color: #111827;
            font-family: "Poppins", Arial, sans-serif;
        }

        .content-wrapper {
            min-height: 100vh;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6b7280;
        }

        .text-secondary {
            color: #4b5563;
        }

        .text-danger {
            color: #dc2626;
        }

        .fw-bold {
            font-weight: 800;
        }

        .mb-4 {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            display: block;
            width: 100%;
        }

        .btn {
            border: 0;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn:disabled {
            cursor: wait;
            opacity: 0.78;
        }

        .w-100 {
            width: 100%;
        }

        .spinner-border {
            width: 14px;
            height: 14px;
            display: inline-block;
            vertical-align: -2px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .me-2 {
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @stack('style-page')
</head>
<body>
    <main class="content-wrapper">
        @yield('content')
    </main>

    <script>
        function btnLoading(el) {
            const form = el.closest('form');

            if (form && !form.checkValidity()) {
                return true;
            }

            window.setTimeout(function () {
                el.disabled = true;
                el.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Memproses...';
            }, 0);

            return true;
        }
    </script>
    @stack('script-page')
</body>
</html>
