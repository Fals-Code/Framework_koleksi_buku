(function () {
    'use strict';

    const config = window.AntrianSseConfig || {};
    const state = {
        source: null,
        reconnectTimer: null,
        reconnectDelay: 1000,
        maxReconnectDelay: 15000,
        lastCalledKey: null,
        boardStarted: false,
        requestBusy: false
    };

    const get = (id) => document.getElementById(id);
    const text = (value, fallback = '-') => value || fallback;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    }

    function connectQueueStream() {
        if (!window.EventSource || !config.sseUrl) {
            console.error('EventSource tidak tersedia atau URL SSE belum disetel.');
            return;
        }

        closeQueueStream();
        state.source = new EventSource(config.sseUrl);

        state.source.addEventListener('open', () => {
            state.reconnectDelay = 1000;
        });

        state.source.addEventListener('queue-update', (event) => {
            try {
                const payload = JSON.parse(event.data);
                handleQueueUpdate(payload);
            } catch (error) {
                console.error('Gagal membaca data SSE antrian:', error);
            }
        });

        state.source.addEventListener('error', () => {
            scheduleReconnect();
        });
    }

    function closeQueueStream() {
        if (state.source) {
            state.source.close();
            state.source = null;
        }

        if (state.reconnectTimer) {
            clearTimeout(state.reconnectTimer);
            state.reconnectTimer = null;
        }
    }

    function scheduleReconnect() {
        if (state.reconnectTimer) {
            return;
        }

        if (state.source) {
            state.source.close();
            state.source = null;
        }

        state.reconnectTimer = setTimeout(() => {
            state.reconnectTimer = null;
            connectQueueStream();
            state.reconnectDelay = Math.min(state.reconnectDelay * 2, state.maxReconnectDelay);
        }, state.reconnectDelay);
    }

    function handleQueueUpdate(payload) {
        if (config.page === 'admin') {
            renderAdmin(payload);
        }

        if (config.page === 'board') {
            renderBoard(payload);
        }
    }

    function renderAdmin(payload) {
        const current = payload.current_called || (payload.dipanggil && payload.dipanggil[0]);
        get('currentNumberDisplay').textContent = current ? current.nomor_antrian : '-';
        get('currentNameDisplay').textContent = current ? current.nama_pengunjung : 'Belum ada panggilan';

        get('badgeMenunggu').textContent = payload.menunggu.length;
        get('badgeTerlewat').textContent = payload.terlewat.length;

        renderAdminList('listMenunggu', payload.menunggu, 'menunggu');
        renderAdminList('listTerlewat', payload.terlewat, 'terlewat');
    }

    function renderAdminList(containerId, items, type) {
        const container = get(containerId);

        if (!items.length) {
            const icon = type === 'menunggu' ? 'mdi-inbox-multiple' : 'mdi-check-circle-outline';
            const message = type === 'menunggu' ? 'Tidak ada antrian' : 'Tidak ada yang terlewat';
            container.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="mdi ${icon} fs-1"></i>
                    <p class="mt-2">${message}</p>
                </div>
            `;
            return;
        }

        container.innerHTML = items.map((item) => {
            const button = type === 'menunggu'
                ? `<button class="btn btn-sm btn-outline-danger rounded-pill btn-skip" data-id="${item.id}" title="Lewati"><i class="mdi mdi-close"></i></button>`
                : `<button class="btn btn-sm btn-outline-success rounded-pill btn-recall" data-id="${item.id}" title="Panggil Ulang"><i class="mdi mdi-reload"></i></button>`;
            const numberClass = type === 'terlewat' ? ' text-danger' : '';

            return `
                <div class="list-group-item d-flex justify-content-between align-items-center" id="item-${item.id}">
                    <div>
                        <h5 class="mb-1 fw-bold${numberClass}">${escapeHtml(item.nomor_antrian)}</h5>
                        <small class="text-muted">${escapeHtml(item.nama_pengunjung)}</small>
                    </div>
                    ${button}
                </div>
            `;
        }).join('');
    }

    function renderBoard(payload) {
        const current = payload.current_called || (payload.dipanggil && payload.dipanggil[0]);

        get('boardCurrentNum').textContent = current ? current.nomor_antrian : '--';
        get('boardCurrentName').textContent = current ? current.nama_pengunjung : 'Menunggu Panggilan';
        get('boardCurrentPurpose').textContent = current ? text(current.keperluan, 'Keperluan belum diisi') : '-';

        renderBoardWaitingList(payload.menunggu || []);
        renderBoardCallHistory(payload.riwayat_panggilan || payload.dipanggil || []);

        if (current) {
            const calledKey = `${current.id}-${current.waktu_dipanggil || payload.updated_at}`;
            if (state.lastCalledKey && state.lastCalledKey !== calledKey) {
                announceCall(current);
            }
            state.lastCalledKey = calledKey;
        }
    }

    function renderBoardWaitingList(items) {
        const container = get('boardWaitingList');

        if (!items.length) {
            container.innerHTML = '<div class="text-center text-muted p-5">Belum ada antrian menunggu</div>';
            return;
        }

        container.innerHTML = items.slice(0, 8).map((item) => `
            <div class="waiting-item">
                <div>
                    <div class="waiting-num">${escapeHtml(item.nomor_antrian)}</div>
                    <div class="waiting-name">${escapeHtml(item.nama_pengunjung)}</div>
                </div>
                <small class="text-muted">${escapeHtml(text(item.keperluan, ''))}</small>
            </div>
        `).join('');
    }

    function renderBoardCallHistory(items) {
        const container = get('boardCallHistory');
        if (!container) {
            return;
        }

        if (!items.length) {
            container.innerHTML = '<div class="text-center text-muted p-4">Belum ada riwayat panggilan</div>';
            return;
        }

        container.innerHTML = items.slice(0, 5).map((item) => `
            <div class="history-item">
                <div class="history-num">${escapeHtml(item.nomor_antrian)}</div>
                <div>
                    <div class="history-name">${escapeHtml(item.nama_pengunjung)}</div>
                    <div class="history-time">${escapeHtml(formatCallTime(item.waktu_dipanggil))}</div>
                </div>
            </div>
        `).join('');
    }

    function formatCallTime(value) {
        if (!value) {
            return '-';
        }

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    function announceCall(item) {
        // Browser autoplay policy blocks sound until a user interacts with the page.
        // The board enables dingdong + speech only after the start overlay is clicked.
        if (!state.boardStarted) {
            return;
        }

        playDingDong();
        window.setTimeout(() => speakCall(item), 900);
    }

    function playDingDong() {
        const audio = get('audioPlayer');
        if (!audio) {
            return;
        }

        audio.currentTime = 0;
        audio.play().catch((error) => {
            console.warn('Audio dingdong belum bisa diputar:', error);
        });
    }

    function speakCall(item) {
        if (!('speechSynthesis' in window)) {
            console.warn('Web Speech API tidak tersedia di browser ini.');
            return;
        }

        window.speechSynthesis.cancel();

        const message = `Nomor antrian ${item.nomor_antrian}, atas nama ${item.nama_pengunjung}, silakan menuju loket.`;
        const utterance = new SpeechSynthesisUtterance(message);
        utterance.lang = 'id-ID';
        utterance.rate = 0.9;
        utterance.pitch = 1;

        const voices = window.speechSynthesis.getVoices();
        const indonesiaVoice = voices.find((voice) => voice.lang === 'id-ID' || voice.lang.startsWith('id'));
        if (indonesiaVoice) {
            utterance.voice = indonesiaVoice;
        }

        window.speechSynthesis.speak(utterance);
    }

    async function postQueueAction(url) {
        if (!url || state.requestBusy) {
            return;
        }

        state.requestBusy = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });

            const result = await response.json().catch(() => ({}));
            if (!response.ok || result.success === false) {
                throw new Error(result.message || 'Aksi antrian gagal diproses.');
            }
        } catch (error) {
            console.error(error);
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message,
                    confirmButtonColor: '#b66dff'
                });
            } else {
                alert(error.message);
            }
        } finally {
            state.requestBusy = false;
        }
    }

    function bindAdminActions() {
        const btnPanggil = get('btnPanggil');
        if (btnPanggil) {
            btnPanggil.addEventListener('click', () => postQueueAction(config.routes.panggil));
        }

        document.addEventListener('click', (event) => {
            const skipButton = event.target.closest('.btn-skip');
            const recallButton = event.target.closest('.btn-recall');

            if (skipButton) {
                postQueueAction(`${config.routes.skip}/${skipButton.dataset.id}`);
            }

            if (recallButton) {
                postQueueAction(`${config.routes.recall}/${recallButton.dataset.id}`);
            }
        });
    }

    window.startSystem = function () {
        state.boardStarted = true;

        const overlay = get('startOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }

        const audio = get('audioPlayer');
        if (audio) {
            audio.load();
        }

        if ('speechSynthesis' in window) {
            window.speechSynthesis.getVoices();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        if (config.page === 'admin') {
            bindAdminActions();
        }

        connectQueueStream();
    });

    window.addEventListener('beforeunload', closeQueueStream);
}());
