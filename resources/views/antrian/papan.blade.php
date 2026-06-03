@extends('layouts.app')

@push('style-page')
<style>
    /* Fullscreen Board Setup */
    .navbar, .sidebar, .footer { display: none !important; }
    .main-panel { width: 100% !important; padding: 0 !important; }
    .page-body-wrapper { padding-top: 0 !important; }
    
    body { background: #0f172a; color: white; overflow: hidden; font-family: 'Inter', sans-serif; }
    .content-wrapper { background: #0f172a; padding: 0 !important; min-height: 100vh; display: flex; }

    /* Layout grid */
    .board-container { display: flex; width: 100%; height: 100vh; }
    
    /* Left Panel - Current Caller */
    .board-main {
        flex: 7;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        position: relative;
    }

    .pulse-ring {
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        border: 2px solid rgba(182, 109, 255, 0.1);
        animation: pulsing 3s infinite ease-out;
    }
    .pulse-ring:nth-child(2) { animation-delay: 1s; width: 700px; height: 700px; }
    .pulse-ring:nth-child(3) { animation-delay: 2s; width: 800px; height: 800px; }

    @keyframes pulsing {
        0% { transform: scale(0.5); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    .current-label { font-size: 3rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 5px; margin-bottom: 20px; z-index: 10; }
    .current-number {
        font-size: 15rem;
        font-weight: 900;
        background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
        z-index: 10;
        text-shadow: 0 10px 30px rgba(79, 172, 254, 0.2);
    }
    .current-name { font-size: 4rem; font-weight: bold; margin-top: 30px; color: #f8fafc; z-index: 10; }
    .current-purpose { font-size: 2rem; margin-top: 12px; color: #cbd5e1; z-index: 10; max-width: 80%; text-align: center; }
    
    /* Right Panel - Queue List */
    .board-sidebar {
        flex: 3;
        background: #1e293b;
        border-left: 1px solid rgba(255,255,255,0.05);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 30px;
        background: rgba(0,0,0,0.2);
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        color: #e2e8f0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .waiting-list { flex: 1; overflow: hidden; padding: 20px; }
    .history-list {
        max-height: 34vh;
        overflow: hidden;
        padding: 18px 20px 24px;
        border-top: 1px solid rgba(255,255,255,0.05);
        background: #172033;
    }
    
    .waiting-item {
        background: #27364a;
        border-radius: 10px;
        padding: 20px 30px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .waiting-num { font-size: 2.5rem; font-weight: bold; color: #b66dff; }
    .waiting-name { font-size: 1.5rem; color: #cbd5e1; }
    .history-item {
        display: grid;
        grid-template-columns: 92px 1fr;
        gap: 14px;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .history-num { font-size: 1.7rem; font-weight: 800; color: #38bdf8; }
    .history-name { font-size: 1.05rem; color: #e2e8f0; line-height: 1.2; }
    .history-time { font-size: 0.85rem; color: #94a3b8; margin-top: 4px; }

    /* Start Overlay for Audio Policy */
    #startOverlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.95); z-index: 9999;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
    }
    .btn-start {
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        border: none; border-radius: 50px; padding: 20px 50px;
        font-size: 2rem; font-weight: bold; color: white; cursor: pointer;
        box-shadow: 0 10px 30px rgba(106, 17, 203, 0.4);
        transition: transform 0.2s;
    }
    .btn-start:hover { transform: scale(1.05); }

</style>
@endpush

@section('content')
<!-- Overlay untuk policy browser audio -->
<div id="startOverlay">
    <i class="mdi mdi-volume-high mb-4" style="font-size: 5rem; color: #b66dff;"></i>
    <h2 class="mb-4">Sistem Suara Antrian</h2>
    <p class="text-muted mb-5 fs-4">Klik tombol di bawah untuk mengaktifkan sistem papan antrian dan suara.</p>
    <button class="btn-start" onclick="startSystem()">Mulai Papan Antrian</button>
</div>

<!-- Audio Player -->
<audio src="{{ asset('audio/dingdong.mp3') }}" id="audioPlayer" preload="auto"></audio>

<div class="board-container">
    <div class="board-main">
        <div class="pulse-ring"></div>
        <div class="pulse-ring"></div>
        <div class="pulse-ring"></div>
        
        <div class="current-label">Nomor Antrian</div>
        <div class="current-number" id="boardCurrentNum">--</div>
        <div class="current-name" id="boardCurrentName">Menunggu Panggilan</div>
        <div class="current-purpose" id="boardCurrentPurpose">-</div>
    </div>
    
    <div class="board-sidebar">
        <div class="sidebar-header">Antrian Selanjutnya</div>
        <div class="waiting-list" id="boardWaitingList">
            <!-- List antrian akan di-render di sini via JS nanti -->
        </div>
        <div class="sidebar-header">Riwayat Panggilan</div>
        <div class="history-list" id="boardCallHistory">
            <!-- Riwayat panggilan akan di-render di sini via JS -->
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    let systemStarted = false;
    let lastCalledId = null;

    function startSystem() {
        document.getElementById('startOverlay').style.display = 'none';
        
        // Putar audio kosong sebentar untuk unlock Web Audio API di beberapa browser
        const audio = document.getElementById('audioPlayer');
        audio.play().catch(e => console.log('Audio unlock failed:', e));
        setTimeout(() => { audio.pause(); audio.currentTime = 0; }, 100);

        // Ucapkan pesan sambutan untuk unlock SpeechSynthesis
        if ('speechSynthesis' in window) {
            const msg = new SpeechSynthesisUtterance("Sistem antrian siap digunakan.");
            msg.lang = 'id-ID';
            msg.volume = 0; // mute sambutan
            window.speechSynthesis.speak(msg);
        }

        systemStarted = true;
        initSSE();
    }

    function initSSE() {
        const sseUrl = "{{ route('antrian.sse.stream') }}";
        let source = new EventSource(sseUrl);

        source.addEventListener('queue-update', function(event) {
            const data = JSON.parse(event.data);
            updateBoardUI(data);
        });

        source.onerror = function(error) {
            console.error('SSE Error di Papan:', error);
            // Browser otomatis reconnect
        };
    }

    function updateBoardUI(data) {
        // Cek jika ada antrian yang dipanggil dan berbeda dari yang sebelumnya
        if (data.current_called && data.action === 'call') {
            const currentId = data.current_called.id;
            
            // Jika nomor yang dipanggil berubah, jalankan animasi dan suara
            if (currentId !== lastCalledId) {
                lastCalledId = currentId;
                
                // Update tampilan utama
                $('#boardCurrentNum').text(data.current_called.nomor_antrian);
                $('#boardCurrentName').text(data.current_called.nama_pengunjung);

                // Tambahkan efek animasi
                $('#boardCurrentNum').removeClass('animate__animated animate__zoomIn').addClass('animate__animated animate__zoomIn');
                
                // Mainkan suara jika sistem sudah dimulai
                if (systemStarted) {
                    playCallSound(data.current_called.nomor_antrian, data.current_called.nama_pengunjung);
                }
            }
        }

        // Update keperluan di papan utama
        if (data.current_called && data.current_called.keperluan) {
            $('#boardCurrentPurpose').text(data.current_called.keperluan);
        } else {
            $('#boardCurrentPurpose').text('-');
        }

        // Update list menunggu (hanya tampilkan 5 teratas)
        let htmlWaiting = '';
        const limitWaiting = data.menunggu.slice(0, 5);
        
        if (limitWaiting.length > 0) {
            limitWaiting.forEach(item => {
                htmlWaiting += `
                    <div class="waiting-item">
                        <div class="waiting-num">${item.nomor_antrian}</div>
                        <div class="waiting-name">${item.nama_pengunjung}</div>
                    </div>
                `;
            });
        } else {
            htmlWaiting = `<div class="text-center text-muted p-4 fs-4 mt-5">Tidak ada antrian</div>`;
        }
        $('#boardWaitingList').html(htmlWaiting);

        // Update Riwayat Panggilan
        let htmlHistory = '';
        // Hindari menampilkan current_called di history jika sama, dan batasi 5
        const historyList = (data.riwayat_panggilan || []).filter(item => {
            return data.current_called ? item.id !== data.current_called.id : true;
        }).slice(0, 5);

        if (historyList.length > 0) {
            historyList.forEach(item => {
                // Parse waktu_dipanggil jika ada (contoh: "2023-01-01T10:00:00.000000Z")
                let timeStr = '';
                if (item.waktu_dipanggil) {
                    const dateObj = new Date(item.waktu_dipanggil);
                    timeStr = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                }
                
                htmlHistory += `
                    <div class="history-item">
                        <div class="history-num">${item.nomor_antrian}</div>
                        <div>
                            <div class="history-name">${item.nama_pengunjung}</div>
                            <div class="history-time"><i class="mdi mdi-clock-outline me-1"></i> ${timeStr}</div>
                        </div>
                    </div>
                `;
            });
        } else {
            htmlHistory = `<div class="text-center text-muted p-3 mt-2">Belum ada riwayat</div>`;
        }
        $('#boardCallHistory').html(htmlHistory);
    }

    function playCallSound(nomor, nama) {
        if (!systemStarted) return;
        
        const audio = document.getElementById('audioPlayer');
        
        // Format nomor untuk dieja dengan jelas, contoh "A 0 0 1"
        const nomorEja = nomor.split('').join(' ');

        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); // Batalkan suara yang sedang berjalan

            const pesan = new SpeechSynthesisUtterance(
                `Nomor antrian, ${nomorEja}. Atas nama ${nama}. Silakan masuk.`
            );
            pesan.lang  = 'id-ID';
            pesan.rate  = 0.85; 
            pesan.pitch = 1.0; 
            pesan.volume = 1.0;

            audio.currentTime = 0;
            audio.play().catch(e => console.error("Gagal memutar mp3:", e));

            // Mainkan text-to-speech setelah ting-tong selesai
            audio.onended = function() {
                window.speechSynthesis.speak(pesan);
            };
        } else {
            // Fallback jika tidak support web speech api
            audio.currentTime = 0;
            audio.play();
        }
    }
</script>
<!-- Tambahkan Animate.css untuk animasi -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

