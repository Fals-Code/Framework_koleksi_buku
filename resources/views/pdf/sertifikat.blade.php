<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { 
            margin: 0; 
            size: a4 landscape;
        }
        body { 
            margin: 0; 
            padding: 0; 
            width: 297mm;
            height: 210mm;
            background-color: white;
            font-family: 'serif';
            overflow: hidden;
        }
        .outer-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 12mm solid #1a237e; /* Navy Blue */
            box-sizing: border-box;
            z-index: 1;
        }
        .inner-frame {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 1.5mm solid #c0c0c0; /* Silver Accent */
            box-sizing: border-box;
            z-index: 2;
            width: calc(297mm - 24mm);
            height: calc(210mm - 24mm);
        }
        .main-content {
            display: table;
            width: 100%;
            height: 72%;
        }
        .content-middle {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 15mm;
        }
        .header-title {
            font-size: 42pt;
            font-weight: bold;
            color: #1a237e;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 8pt;
        }
        .header-subtitle {
            font-size: 16pt;
            color: #757575;
            font-weight: bold;
            margin-top: -1mm;
            letter-spacing: 5pt;
        }
        .cert-no {
            font-family: 'sans-serif';
            font-size: 10pt;
            margin: 4mm 0 6mm 0;
            color: #444;
        }
        .given-text {
            font-size: 16pt; 
            font-style: italic;
            color: #333;
        }
        .recipient-name {
            font-size: 36pt;
            font-weight: bold;
            color: #000;
            border-bottom: 2pt solid #1a237e;
            display: inline-block;
            padding-bottom: 1mm;
            margin: 2mm 0;
        }
        .role-text {
            font-size: 22pt;
            font-weight: bold;
            color: #1a237e;
            margin: 1mm 0;
            text-transform: uppercase;
        }
        .description {
            font-family: 'sans-serif';
            font-size: 11pt;
            line-height: 1.5;
            margin: 4mm 25mm 0 25mm;
            color: #444;
        }
        .signature-section {
            position: absolute;
            bottom: 10mm;
            width: 100%;
            left: 0;
        }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sig-box {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            font-family: 'sans-serif';
            font-size: 10pt;
        }
        .sig-space {
            height: 20mm;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            color: #000;
        }
        .sig-meta {
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="outer-border"></div>
    <div class="inner-frame">
        <div class="main-content">
            <div class="content-middle">
                <div class="header-title">SERTIFIKAT</div>
                <div class="header-subtitle">KOMPETENSI</div>
                <div class="cert-no">Ref. No: 2026/TECH-DEV/AI/{{ rand(1000, 9999) }}</div>

                <div class="given-text">Diberikan secara terhormat kepada:</div>
                <div class="recipient-name">ALFIA RIZQY HANIFAH, S.KOM.</div>

                <div style="font-size: 14pt; margin-top: 4mm;">Atas keberhasilan dan kontribusinya sebagai:</div>
                <div class="role-text">FULL-STACK WEB DEVELOPER</div>

                <div class="description">
                    Dalam program pelatihan intensif <strong>"Mastering Laravel 11 & Gemini AI Integration"</strong> 
                    yang diselenggarakan oleh Departemen Teknologi Informasi Global pada tanggal 
                    19 Februari 2026 secara daring.
                </div>
            </div>
        </div>

        <div class="signature-section">
            <table class="sig-table">
                <tr>
                    <td class="sig-box">
                        <div>Direktur Pelaksana</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">Budi Santoso, M.T.</div>
                        <div class="sig-meta">NIDN. 0422088701</div>
                    </td>
                    <td class="sig-box">
                        <div style="visibility: hidden;">Spacer</div>
                    </td>
                    <td class="sig-box">
                        <div>Lead Instructor</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">Prof. Dr. Ir. H. Ahmad Fauzi</div>
                        <div class="sig-meta">Certified Google Developer Expert</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>