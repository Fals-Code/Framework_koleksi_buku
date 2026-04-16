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
            background: #fff;
        }

table {
    margin-left: 0.24cm !important; 
    margin-top:  0.28cm !important;
    border-collapse: collapse;
    table-layout: fixed;
    width: 20.5cm;
}
        .label-td {
            padding: 0 !important;
            margin:  0 !important;
            width:   4.1cm;
            height:  2.0cm;
            vertical-align: top;
        }

        /* Box konten label */
        .label-box {
            width:      3.8cm;
            height:     1.9cm;
            margin:     0;
            padding:    2px 3px 1px 3px;
            display:    block;
            box-sizing: border-box;
            text-align: center;
            overflow:   hidden;
            border:     none;
        }

        /* ID sangat kecil di baris paling atas */
        .id-barang {
            font-size:    5pt;
            color:        #aaa;
            line-height:  1;
            margin-bottom: 1px;
        }

        /* Barcode — lebar penuh label, tinggi dikompres agar muat */
        .barcode-wrap {
            width:    3.4cm;
            height:   0.65cm;
            overflow: hidden;
            margin:   0 auto 1px auto;
        }
        .barcode-wrap img {
            width:  100%;
            height: 0.65cm;
        }

        /* Nama barang — bold, nowrap dengan ellipsis */
        .nama-barang {
            font-size:     7.5pt;
            font-weight:   bold;
            line-height:   1.1;
            margin:        1px 0 0 0;
            white-space:   nowrap;
            overflow:      hidden;
            text-overflow: ellipsis;
        }

        /* Harga */
        .harga-barang {
            font-size:   9pt;
            font-weight: bold;
            color:       #000;
            line-height: 1.1;
        }
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
                            @php
                                $item = $items->shift();
                                // height=40px, barWidth=2px
                                $barcodeHtml = \App\Helpers\BarcodeHelper::img(
                                    (string) $item->id_barang,
                                    40,
                                    2,
                                    ''
                                );
                            @endphp

                            <div class="label-box">
                                <div class="id-barang">{{ $item->id_barang }}</div>
                                <div class="barcode-wrap">{!! $barcodeHtml !!}</div>
                                <div class="nama-barang">{{ $item->nama }}</div>
                                <div class="harga-barang">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                            </div>

                        @else
                            <div style="width:4.1cm; height:2.0cm;"></div>
                        @endif

                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>