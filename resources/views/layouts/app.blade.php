<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>VOKASI PERPUS | Sistem Inventaris</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        :root { --primary-gradient: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%); }
        .navbar .navbar-menu-wrapper .navbar-nav .nav-item.dropdown .dropdown-menu.navbar-dropdown {
            top: 100% !important; right: 0 !important; left: auto !important; margin-top: 10px !important;
            position: absolute !important; display: none; border: none; box-shadow: 0 5px 25px rgba(0,0,0,0.1); z-index: 2000;
        }
        .navbar .navbar-menu-wrapper .navbar-nav .nav-item.dropdown .dropdown-menu.navbar-dropdown.show {
            display: block !important; opacity: 1 !important; visibility: visible !important; animation: dropdownFade 0.3s ease;
        }
        @keyframes dropdownFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .card { border-radius: 15px !important; border: none !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important; }
        .bg-gradient-primary { background: var(--primary-gradient) !important; }
        .pulse-animation { animation: pulse-red 2s infinite; border-radius: 50%; }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(254, 114, 146, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(254, 114, 146, 0); }
            100% { box-shadow: 0 0 0 0 rgba(254, 114, 146, 0); }
        }
#preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.5s ease;
}

.loader-wrapper {
    text-align: center;
    position: relative;
}

.loader-circle {
    width: 60px;
    height: 60px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #b66dff;
    border-right: 3px solid #b66dff;
    border-radius: 50%;
    margin: 0 auto 20px;
    animation: spin 1s cubic-bezier(0.5, 0.1, 0.4, 0.9) infinite;
}

.loader-logo {
    font-family: 'Poppins', sans-serif;
    letter-spacing: 2px;
    font-size: 14px;
    font-weight: 800;
}

.loader-logo .vokasi { color: #343a40; }
.loader-logo .perpus { color: #b66dff; }

.loader-line {
    width: 40px;
    height: 2px;
    background: var(--primary-gradient);
    margin: 8px auto 0;
    border-radius: 2px;
    animation: lineGrow 1.5s ease-in-out infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes lineGrow {
    0%, 100% { width: 10px; opacity: 0.2; }
    50% { width: 50px; opacity: 1; }
}
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @media print {
            .navbar, .sidebar, .footer, .btn, .search-field { display: none !important; }
            .main-panel { width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .content-wrapper { padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
    @stack('style-page')
</head>
<body>
    <div id="preloader">
    <div class="loader-wrapper">
        <div class="loader-circle"></div>
        <div class="loader-logo">
            <span class="vokasi">VOKASI</span>
            <span class="perpus">PERPUS</span>
        </div>
        <div class="loader-line"></div>
    </div>
</div>
    <div class="container-scroller">
        @include('layouts.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">@yield('content')</div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailNotifModal" tabindex="-1" aria-hidden="true" style="z-index: 2050;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
                <div class="modal-header border-0 bg-gradient-primary text-white p-4" style="border-radius: 25px 25px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="mdi mdi-bell-outline me-2"></i> Detail Aktivitas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <div class="mb-4"><i id="notifIcon" class="mdi mdi-information-variant text-primary" style="font-size: 80px;"></i></div>
                    <h3 id="notifTitleDisplay" class="fw-bold mb-3"></h3>
                    <p id="notifMessageDisplay" class="text-muted fs-5"></p>
                    <div class="badge bg-light text-dark rounded-pill px-3 py-2 mt-4"><i class="mdi mdi-clock-outline me-1"></i> <span id="notifTimeDisplay"></span></div>
                </div>
                <div class="modal-footer border-0 p-4 justify-content-center">
                    <button type="button" class="btn btn-gradient-primary btn-lg rounded-pill px-5" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script> --}}
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        function hidePreloader() {
            const pre = document.getElementById('preloader');
            if (pre && pre.style.display !== 'none') {
                pre.style.opacity = '0';
                setTimeout(() => {
                    pre.style.display = 'none';
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', hidePreloader);
        window.addEventListener('load', hidePreloader);
        setTimeout(hidePreloader, 3000);

        let currentNotifId = null;
        $(document).ready(function() {
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                var $menu = $(this).next('.dropdown-menu');
                $('.dropdown-menu').not($menu).removeClass('show');
                $menu.toggleClass('show');
                e.stopPropagation();
            });
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.nav-item.dropdown').length) { $('.dropdown-menu').removeClass('show'); }
            });
            @if(session('success'))
                if (window.Swal) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 1500, iconColor: '#b66dff' });
                }
            @endif
            @if(session('error'))
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Kesalahan!', text: "{{ session('error') }}", confirmButtonColor: '#b66dff' });
                }
            @endif
            $('#detailNotifModal').on('hidden.bs.modal', function () {
                if (currentNotifId) {
                    $.ajax({
                        url: `/notifications/${currentNotifId}/read`,
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() { 
                            currentNotifId = null; 
                            fetchLatestNotifications(); // Update UI immediately
                        }
                    });
                }
            });

            // Polling for live notifications
            let lastNotifId = null;
            function fetchLatestNotifications() {
                $.ajax({
                    url: "{{ route('notifications.latest') }}",
                    method: 'GET',
                    success: function(data) {
                        updateNotificationUI(data);
                        
                        // Check for new notification to show toast
                        if (data.notifications.length > 0) {
                            let newest = data.notifications[0];
                            if (lastNotifId && newest.id !== lastNotifId) {
                                showNotificationToast(newest);
                            }
                            lastNotifId = newest.id;
                        }
                    }
                });
            }

            function updateNotificationUI(data) {
                const badge = $('#notifCountBadge');
                if (data.unread_count > 0) {
                    badge.show().text(data.unread_count > 9 ? '9+' : data.unread_count);
                } else {
                    badge.hide();
                }

                const container = $('#notifListContainer');
                if (data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(notif => {
                        const bg = notif.type === 'success' ? 'bg-gradient-success' : 
                                   (notif.type === 'danger' ? 'bg-gradient-danger' : 'bg-gradient-info');
                        const icon = notif.type === 'success' ? 'mdi-check-circle' : 
                                   (notif.type === 'danger' ? 'mdi-delete-alert' : 'mdi-information');
                        
                        html += `
                            <a class="dropdown-item preview-item py-3" href="javascript:void(0);" 
                               onclick="showNotifDetail('${notif.title}', '${notif.message}', '${notif.created_at}', '${notif.id}')">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon ${bg} rounded-circle"><i class="mdi ${icon} text-white"></i></div>
                                </div>
                                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1 text-dark fw-bold">${notif.title}</h6>
                                    <p class="text-muted ellipsis mb-0 small">${notif.message}</p>
                                    <small class="text-primary mt-1" style="font-size: 9px;">${notif.created_at}</small>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        `;
                    });
                    container.html(html);
                } else {
                    container.html('<div class="p-4 text-center"><i class="mdi mdi-bell-off-outline text-muted fs-3"></i><p class="text-muted small mt-2">Belum ada aktivitas tercatat</p></div>');
                }
            }

            function showNotificationToast(notif) {
                if (!window.Swal) {
                    return;
                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: notif.type || 'info',
                    title: notif.title,
                    text: notif.message
                });
            }

            // Initial and interval fetch
            @auth
                fetchLatestNotifications();
                setInterval(fetchLatestNotifications, 30000); // Poll every 30 seconds
            @endauth
        });
        function showNotifDetail(title, message, time, id = null) {
            currentNotifId = id;
            $('#notifTitleDisplay').text(title);
            $('#notifMessageDisplay').text(message);
            $('#notifTimeDisplay').text(time);
            var myModal = new bootstrap.Modal(document.getElementById('detailNotifModal'));
            myModal.show();
        }
        function confirmDelete(url) {
            if (!window.Swal) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    let form = document.createElement('form');
                    form.action = url; form.method = 'POST';
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form); form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?', text: "Data yang dihapus tidak dapat dikembalikan!", icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#b66dff', cancelButtonColor: '#fe72af', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = url; form.method = 'POST';
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form); form.submit();
                }
            });
        }
        function btnLoading(el) {
    const $el = $(el);
    if ($el.hasClass('disabled')) return;

    const originalContent = $el.html();
    const originalHeight = $el.outerHeight();
    const originalWidth = $el.outerWidth();

    $el.css({
        'min-height': originalHeight + 'px',
        'min-width': originalWidth + 'px'
    });

    $el.addClass('disabled').prop('disabled', true);
    
    $el.html(`
        <div class="d-flex align-items-center justify-content-center">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <span>Memproses...</span>
        </div>
    `);

    if ($el.closest('form').length > 0) {
        $el.closest('form').submit();
    }
}
    </script>
    @stack('script-page')
</body>
</html>
