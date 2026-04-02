<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - {{ $pesanan->nomor_pesanan }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .header { margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .info { margin-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; }
        .table td { padding: 2px 0; vertical-align: top; }
        .footer { margin-top: 15px; font-size: 10px; }
        @media print {
            .no-print { display: none; }
            body { width: 100%; border: none; padding: 0; }
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #b66dff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-family: sans-serif;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 10px 0;">
        <a href="javascript:window.print()" class="btn-print">CETAK STRUK</a>
        <a href="{{ route('kantin.index') }}" style="display:block; text-align:center; margin-top:10px; color:#666; text-decoration:none; font-family:sans-serif; font-size:11px;">Kembali ke Menu</a>
    </div>

    <div class="header text-center">
        <h2>{{ $pesanan->vendor->nama_warung }}</h2>
        <p>Kantin Vokasi UNESA<br>Surabaya, Jawa Timur</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <table class="table">
            <tr>
                <td>No. Pesanan:</td>
                <td class="text-right">{{ $pesanan->nomor_pesanan }}</td>
            </tr>
            <tr>
                <td>Tanggal:</td>
                <td class="text-right">{{ $pesanan->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pelanggan:</td>
                <td class="text-right">{{ $pesanan->nama_pelanggan }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table class="table">
        <thead>
            <tr>
                <th align="left">Menu</th>
                <th align="center">Qty</th>
                <th align="right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan->detailPesanan as $detail)
            <tr>
                <td>{{ $detail->menu->nama_makanan }}</td>
                <td align="center">{{ $detail->qty }}</td>
                <td align="right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="table" style="font-weight: bold;">
        <tr>
            <td>TOTAL</td>
            <td class="text-right">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="info">
        <p><strong>Catatan:</strong><br>{{ $pesanan->catatan ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($pesanan->status) }}</p>
    </div>

    <div class="footer text-center">
        <p>Terima kasih telah berbelanja!<br>Semoga harimu menyenangkan.</p>
        <div style="margin-top: 10px;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('kantin.track', $pesanan->id)) }}" alt="QR Code" style="width: 80px; height: 80px;">
        </div>
        <p style="margin-top: 5px;">Scan untuk lacak pesanan</p>
    </div>

    <script>
        // Auto print window when loaded
        window.onload = function() {
            // Uncomment the line below if you want auto-print
            // window.print();
        }
    </script>
</body>
</html>
