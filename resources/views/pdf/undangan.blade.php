<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm; }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        /* Kop Surat */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .header-logo { width: 80px; }
        .header-text { text-align: center; }
        .header-text h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header-text h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; font-style: italic; }

        .surat-meta { margin-top: 20px; width: 100%; }
        .content { margin-top: 30px; text-align: justify; }
        .detail-acara { margin: 20px 0 20px 40px; }
        .footer-table { width: 100%; margin-top: 50px; }
        .sig-box { width: 40%; text-align: center; float: right; }
        .sig-space { height: 25mm; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="https://upload.wikimedia.org/wikipedia/id/0/08/Logo_UNAIR.png" width="80">
            </td>
            <td class="header-text">
                <h1>UNIVERSITAS AIRLANGGA</h1>
                <h2>FAKULTAS ILMU KESEHATAN, KEBIDANAN, DAN ILMU ALAM</h2>
                <p>Jl. Gajah Mada No.183, Kec. Giri, Kabupaten Banyuwangi, Jawa Timur</p>
                <p>Laman: https://fikkia.unair.ac.id | Email: info@fikkia.unair.ac.id</p>
            </td>
        </tr>
    </table>

    <table class="surat-meta">
        <tr>
            <td width="15%">Nomor</td>
            <td width="2%">:</td>
            <td>{{ $nomor_surat }}</td>
            <td align="right">{{ $tanggal_surat }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><strong>{{ $perihal }}</strong></td>
        </tr>
    </table>

    <div class="content">
        <p>Yth. {{ $nama_tujuan }}<br>di {{ $tempat_tujuan }}</p>

        <p>Dengan hormat,</p>
        <p>Sehubungan dengan upaya peningkatan pemanfaatan Artificial Intelligence dalam dunia akademik, kami bermaksud mengundang Bapak/Ibu untuk hadir pada kegiatan Seminar Nasional yang akan dilaksanakan pada:</p>

        <table class="detail-acara">
            <tr>
                <td width="120px">Hari, Tanggal</td>
                <td width="10px">:</td>
                <td>{{ $hari_acara }}, {{ $tgl_acara }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $waktu_acara }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $lokasi_acara }}</td>
            </tr>
        </table>

        <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami sampaikan terima kasih.</p>
    </div>

    <div class="footer-table">
        <div class="sig-box">
            <p>Dekan,</p>
            <div class="sig-space"></div>
            <p><strong>Dr. Rahadian Indarto Susilo, Dr., Sp.BS. (K)</strong><br>
            NIP. 197712272009121002</p>
        </div>
    </div>
</body>
</html>