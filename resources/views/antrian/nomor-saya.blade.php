@extends('layouts.public-antrian')

@push('style-page')
<style>
    .navbar, .sidebar, .footer {
        display: none !important;
    }

    .main-panel {
        width: 100% !important;
        padding: 0 !important;
        min-height: 100vh !important;
    }

    .page-body-wrapper {
        padding-top: 0 !important;
    }

    .content-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px 16px !important;
        background:
            radial-gradient(circle at top left, rgba(40, 167, 69, 0.14), transparent 32%),
            linear-gradient(135deg, #f8fafc 0%, #e7ecf5 100%);
    }

    .ticket-shell {
        width: 100%;
        max-width: 520px;
    }

    .success-mark {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
        box-shadow: 0 12px 24px rgba(34, 197, 94, 0.24);
        margin-bottom: 18px;
    }

    .success-mark::before {
        content: "";
        width: 18px;
        height: 32px;
        border-right: 5px solid #ffffff;
        border-bottom: 5px solid #ffffff;
        transform: rotate(45deg) translate(-2px, -3px);
    }

    .ticket-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 18px 44px rgba(30, 41, 59, 0.14);
        animation: ticketIn 0.45s ease-out forwards;
    }

    .ticket-card::before,
    .ticket-card::after {
        content: "";
        position: absolute;
        top: 220px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #edf2f8;
        z-index: 2;
    }

    .ticket-card::before {
        left: -16px;
    }

    .ticket-card::after {
        right: -16px;
    }

    .ticket-header {
        padding: 34px 32px 26px;
        text-align: center;
        border-top: 6px solid #22c55e;
    }

    .ticket-title {
        color: #111827;
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .ticket-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 0;
    }

    .ticket-number-area {
        padding: 28px 32px;
        text-align: center;
        border-top: 1px dashed #d7dce5;
        border-bottom: 1px dashed #d7dce5;
        background: #fbfdff;
    }

    .ticket-label {
        color: #6b7280;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .number-display {
        color: #4b49ac;
        font-size: clamp(56px, 15vw, 92px);
        font-weight: 900;
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .ticket-body {
        padding: 28px 32px 32px;
    }

    .visitor-name {
        color: #111827;
        font-size: 20px;
        font-weight: 800;
        text-align: center;
        margin-bottom: 6px;
    }

    .visitor-nim {
        color: #6b7280;
        text-align: center;
        margin-bottom: 18px;
    }

    .ticket-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 22px 0;
    }

    .info-item {
        min-height: 76px;
        padding: 14px;
        border: 1px solid #eef1f6;
        border-radius: 8px;
        background: #ffffff;
    }

    .info-label {
        display: block;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .info-value {
        color: #111827;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.4;
    }

    .next-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        border-radius: 8px;
        color: #24543a;
        background: #ecfdf3;
        border: 1px solid #bbf7d0;
        font-size: 14px;
        line-height: 1.5;
    }

    .next-step-icon {
        width: 22px;
        height: 22px;
        flex: 0 0 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 2px solid #16a34a;
        color: #16a34a;
        font-size: 13px;
        font-weight: 900;
        line-height: 1;
        margin-top: 1px;
    }

    .ticket-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 22px;
    }

    .btn-ticket {
        min-height: 46px;
        border-radius: 8px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-print-ticket {
        color: #ffffff;
        border: 0;
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
    }

    .btn-print-ticket:hover {
        color: #ffffff;
        opacity: 0.92;
    }

    .btn-secondary-ticket {
        color: #4b49ac;
        border: 1px solid #ddd8ff;
        background: #f6f4ff;
        text-decoration: none;
    }

    .btn-secondary-ticket:hover {
        color: #3a398c;
        background: #efebff;
        text-decoration: none;
    }

    @keyframes ticketIn {
        from {
            transform: translateY(18px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @media (max-width: 575.98px) {
        .ticket-header,
        .ticket-number-area,
        .ticket-body {
            padding-left: 22px;
            padding-right: 22px;
        }

        .ticket-info,
        .ticket-actions {
            grid-template-columns: 1fr;
        }

        .ticket-card::before,
        .ticket-card::after {
            top: 214px;
        }
    }

    @media print {
        body {
            background: #ffffff !important;
        }

        .content-wrapper {
            min-height: auto;
            padding: 0 !important;
            background: #ffffff !important;
        }

        .ticket-shell {
            max-width: 100%;
        }

        .ticket-card {
            box-shadow: none !important;
            border: 1px solid #d7dce5;
        }

        .ticket-actions {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="ticket-shell">
    <div class="ticket-card" role="region" aria-label="Tiket nomor antrian">
        <div class="ticket-header">
            <div class="success-mark" aria-hidden="true"></div>
            <h1 class="ticket-title">Pendaftaran Berhasil</h1>
            <p class="ticket-subtitle">Simpan nomor ini dan tunggu sampai dipanggil petugas.</p>
        </div>

        <div class="ticket-number-area">
            <div class="ticket-label">Nomor Antrian</div>
            <div class="number-display">{{ $antrian->nomor_antrian }}</div>
        </div>

        <div class="ticket-body">
            <div class="visitor-name">{{ $antrian->nama_pengunjung }}</div>
            @if($antrian->nim)
                <div class="visitor-nim">NIM: {{ $antrian->nim }}</div>
            @endif

            <div class="ticket-info">
                <div class="info-item">
                    <span class="info-label">Keperluan</span>
                    <span class="info-value">{{ $antrian->keperluan ?: 'Lainnya' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Waktu Daftar</span>
                    <span class="info-value">{{ $antrian->waktu_daftar->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="next-step">
                <span class="next-step-icon" aria-hidden="true">i</span>
                <div>
                    Datang ke ruang tunggu perpustakaan dan pantau papan antrian. Nomor akan dipanggil sesuai urutan pendaftaran.
                </div>
            </div>

            <div class="ticket-actions">
                <button type="button" class="btn btn-ticket btn-print-ticket" onclick="window.print()">
                    Cetak Tiket
                </button>
                <a href="{{ route('antrian.guest') }}" class="btn-ticket btn-secondary-ticket">
                    Ambil Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
