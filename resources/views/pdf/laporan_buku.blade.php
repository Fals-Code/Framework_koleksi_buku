<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { 
            margin: 10mm; 
            size: a4 portrait;
        }
        body { 
            margin: 0; 
            padding: 0; 
            font-family: 'sans-serif';
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3pt solid #1a237e; /* Navy Blue */
            padding-bottom: 5mm;
            margin-bottom: 10mm;
        }
        .header h1 {
            text-transform: uppercase;
            color: #1a237e;
            margin: 0;
            letter-spacing: 2pt;
        }
        .header p {
            margin: 5px 0;
            color: #757575;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #1a237e;
            color: white;
            text-transform: uppercase;
            font-size: 10pt;
            padding: 10px;
            border: 1px solid #c0c0c0; /* Silver Accent */
        }
        td {
            padding: 8px;
            border: 1px solid #e0e0e0;
            font-size: 10pt;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 15mm;
            width: 100%;
        }
        .sig-box {
            float: right;
            width: 200px;
            text-align: center;
        }
        .sig-space {
            height: 20mm;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Inventaris Buku</h1>
        <p>Sistem Manajemen Koleksi Buku Digital</p>
        <div style="font-size: 9pt; color: #444; margin-top: 5px;">
            Ref. No: RPT/{{ date('Y') }}/BK/{{ rand(100, 999) }} | Tanggal Cetak: {{ $date }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="40%">Judul Buku</th>
                <th width="20%">Pengarang</th>
                <th width="20%">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bukus as $key => $buku)
            <tr>
                <td align="center">{{ $key + 1 }}</td>
                <td align="center"><strong>{{ $buku->kode }}</strong></td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->pengarang }}</td>
                <td align="center">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="sig-box">
            <div>Dicetak Oleh,</div>
            <div class="sig-space"></div>
            <div class="sig-name">{{ auth()->user()->name }}</div>
            <div style="font-size: 9pt; color: #666;">Administrator Sistem</div>
        </div>
    </div>
</body>
</html>