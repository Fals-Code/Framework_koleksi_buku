<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { 
            margin: 1cm; 
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #fff;
        }
        .label-container { width: 100%; }
        .label-box {
            width: 30%;
            float: left;
            margin: 1.5%;
            border: 1.5px solid #002d55; /* Biru Vokasi */
            border-radius: 5px;
            height: 48mm;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .label-header {
            background-color: #002d55;
            color: #ffc107; /* Kuning Emas */
            text-align: center;
            font-size: 8pt;
            padding: 5px;
            font-weight: bold;
        }
        .label-content {
            padding: 8px;
            text-align: center;
        }
        .kode-text {
            font-size: 22pt;
            font-weight: 900;
            color: #000;
            display: block;
        }
        .judul-buku {
            font-size: 9pt;
            font-weight: bold;
            height: 2.4em;
            margin: 5px 0;
            overflow: hidden;
        }
        .info-bawah {
            width: 100%;
            border-top: 1px solid #002d55;
            font-size: 7pt;
            background-color: #f8f9fa;
            text-align: center;
            padding: 3px 0;
        }
    </style>
</head>
<body>
    <div class="label-container">
        @foreach($bukus as $buku)
        <div class="label-box">
            <div class="label-header">PERPUSTAKAAN VOKASI UNAIR</div>
            <div class="label-content">
                <span class="kode-text">{{ $buku->kode }}</span>
                <div class="judul-buku">{{ Str::upper(Str::limit($buku->judul, 40)) }}</div>
            </div>
            <div class="info-bawah">
                <strong>{{ $buku->kategori->nama_kategori ?? 'UMUM' }}</strong> | {{ date('Y') }}
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>