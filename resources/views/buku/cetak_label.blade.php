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
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .btn-print {
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-print:hover {
    background: #e5ac00;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.3);
}

.btn-print:active {
    transform: translateY(0);
}

.spinner-print {
    width: 18px;
    height: 18px;
    border: 3px solid rgba(0,45,85,0.3);
    border-top: 3px solid #002d55;
    border-radius: 50%;
    animation: spinPrint 0.8s linear infinite;
}

@keyframes spinPrint {
    to { transform: rotate(360deg); }
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .label-header {
            background-color: #002d55 !important;
            color: #ffc107 !important; 
            text-align: center;
            font-size: 9pt;
            padding: 8px 2px;
            font-weight: bold;
            text-transform: uppercase;
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
    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="background: #ffc107; color: #002d55; width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        </div>
        <div>
            <h3 style="margin:0; font-family: 'Segoe UI', sans-serif;">Pratinjau Label Buku</h3>
            <small style="opacity: 0.8;">Siap mencetak {{ count($bukus) }} label koleksi</small>
        </div>
    </div>
    <button class="btn-print" id="btnPrintFinal" onclick="startPrint()">
        <span>⎙ CETAK SEKARANG</span>
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

    <script>
function startPrint() {
    const btn = document.getElementById('btnPrintFinal');
    const originalHTML = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-print"></div> Menyiapkan Dokumen...';
    btn.style.opacity = '0.8';

    setTimeout(() => {
        window.print();
        
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            btn.style.opacity = '1';
        }, 1000);
    }, 800);
}
</script>
</body>
</html>