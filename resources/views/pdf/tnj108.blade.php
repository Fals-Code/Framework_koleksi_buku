<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <style>
        @page {
            size: A4 portrait;
            margin: 0 !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, sans-serif;
        }

        table {
            margin-left: 0.14cm !important; 
            margin-top: 0.28cm !important;
            border-collapse: collapse;
            table-layout: fixed;
            width: 20.5cm;
        }

        .label-td {
            padding: 0 !important;
            margin: 0 !important;
            width: 4.1cm; 
            height: 2.0cm;
            vertical-align: top;
        }

        .label-box {
            width: 3.8cm;
            height: 1.7cm;
            margin: 0;
            padding: 5px;
            display: block;
            box-sizing: border-box;
            text-align: center;
            overflow: hidden;
            border:none;
        }

        .id-barang { font-size: 7pt; color: #888; }
        .nama-barang { 
            font-size: 8pt; 
            font-weight: bold; 
            line-height: 1.1; 
            margin: 2px 0; 
            height: 2.2em; 
            overflow: hidden; 
        }
        .harga-barang { font-size: 10pt; font-weight: bold; }
    </style>
</head>
<body>
    <table>
        @php $currentSlot = 0; @endphp
        @for ($row = 0; $row < 8; $row++)
            <tr>
                @for ($col = 0; $col < 5; $col++)
                    @php $currentSlot++; @endphp
                    <td class="label-td">
                        @if ($currentSlot > $skipSlots && count($items) > 0)
                            @php $item = $items->shift(); @endphp
                            <div class="label-box">
                                <div class="id-barang">{{ $item->id_barang }}</div>
                                <div class="nama-barang">{{ $item->nama }}</div>
                                <div class="harga-barang">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                            </div>
                        @else
                            <div style="width: 4.1cm; height: 2.0cm;"></div>
                        @endif
                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>