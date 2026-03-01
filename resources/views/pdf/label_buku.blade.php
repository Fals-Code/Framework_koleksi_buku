<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { 
            margin: 0.5cm; 
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .label-container { 
            width: 100%;
            display: block;
        }
        .label-box {
            width: 23%;
            float: left;
            margin: 1%;
            border: 1.5px solid #002d55; 
            border-radius: 5px;
            height: 50mm;
            overflow: hidden;
            background: white;
            box-sizing: border-box;
            page-break-inside: avoid;
            position: relative;
        }
        .label-header {
            background-color: #002d55;
            color: #ffc107; 
            text-align: center;
            font-size: 8pt;
            padding: 6px 2px;
            font-weight: bold;
            text-transform: uppercase;
        }
        /* Konten Tengah */
        .label-content {
            padding: 8px 5px;
            text-align: center;
            height: 30mm;
        }
        .kode-text {
            font-size: 20pt;
            font-weight: 900;
            color: #000;
            margin-bottom: 2px;
            display: block;
        }
        .judul-buku {
            font-size: 9pt;
            font-weight: bold;
            line-height: 1.2;
            color: #333;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        /* Footer */
        .info-bawah {
            position: absolute;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #002d55;
            font-size: 7pt;
            background-color: #f8f9fa;
            text-align: center;
            padding: 5px 0;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="label-container">
        @foreach($bukus as $index => $buku)
            <div class="label-box">
                <div class="label-header">PERPUSTAKAAN VOKASI UNAIR</div>
                
                <div class="label-content">
                    <span class="kode-text">{{ $buku->kode }}</span>
                    <div class="judul-buku">
                        {{ Str::upper($buku->judul) }}
                    </div>
                </div>
                
                <div class="info-bawah">
                    <strong>{{ $buku->kategori->nama_kategori ?? 'UMUM' }}</strong> | {{ date('Y') }}
                </div>
            </div>

            @if(($index + 1) % 4 == 0)
                <div class="clearfix"></div>
            @endif
        @endforeach
    </div>
</body>
</html>