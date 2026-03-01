<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label Buku - Perpustakaan Vokasi UNAIR</title>
    <style>
        @page { 
            margin: 0.5cm; 
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            /* Memaksa browser mencetak warna latar belakang */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .preview-header {
            background: #002d55;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .btn-print {
            background: #ffc107;
            color: #002d55;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .page-simulate {
            background: white;
            width: 280mm; 
            margin: 20px auto;
            padding: 10px;
            min-height: 190mm;
        }

        .label-container { 
            width: 100%;
            display: block;
            clear: both;
        }

        .label-box {
            width: 23%;
            float: left;
            margin: 1%;
            border: 2px solid #002d55; 
            border-radius: 8px;
            height: 52mm;
            overflow: hidden;
            background: white !important;
            box-sizing: border-box;
            position: relative;
            page-break-inside: avoid;
        }

        .label-header {
            background-color: #002d55 !important;
            color: #ffc107 !important; 
            text-align: center;
            font-size: 9pt;
            padding: 8px 2px;
            font-weight: bold;
            text-transform: uppercase;
            /* Penting untuk mencetak header biru */
            -webkit-print-color-adjust: exact !important;
        }

        .label-content {
            padding: 15px 5px;
            text-align: center;
        }

        .kode-text {
            font-size: 24pt;
            font-weight: 900;
            color: #000;
            margin: 0;
            display: block;
            line-height: 1;
        }

        .judul-buku {
            font-size: 10pt;
            font-weight: bold;
            line-height: 1.2;
            color: #333;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .info-bawah {
            position: absolute;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #002d55;
            font-size: 8pt;
            background-color: #f8f9fa !important;
            text-align: center;
            padding: 6px 0;
            -webkit-print-color-adjust: exact !important;
        }

        .clearfix { clear: both; }

        @media print {
            .preview-header { display: none !important; }
            body { background: white !important; }
            .page-simulate {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="preview-header">
        <div>
            <h3 style="margin:0">Pratinjau Label Buku</h3>
            <small>Jumlah terpilih: {{ count($bukus) }} item</small>
        </div>
        <button class="btn-print" onclick="window.print()">
            ⎙ CETAK SEKARANG
        </button>
    </div>

    <div class="page-simulate">
        <div class="label-container">
            @foreach($bukus as $index => $buku)
                <div class="label-box">
                    <div class="label-header">PERPUSTAKAAN VOKASI UNAIR</div>
                    
                    <div class="label-content">
                        <span class="kode-text">{{ $buku->kode }}</span>
                        <div class="judul-buku">
                            {{ \Illuminate\Support\Str::limit($buku->judul, 45) }}
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
            <div class="clearfix"></div>
        </div>
    </div>

</body>
</html>