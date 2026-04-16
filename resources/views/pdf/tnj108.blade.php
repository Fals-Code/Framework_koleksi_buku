{{--
    Label PDF: TNJ108 (A4 Portrait, 5x8 grid = 40 labels)
    Setiap label menampilkan:
    - ID Barang
    - Barcode Code128 (scannable oleh kamera kasir)
    - Nama Barang
    - Harga

    Barcode di-generate menggunakan App\Helpers\BarcodeHelper
--}}
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
            margin-left: 0.14cm !important;
            margin-top:  0.28cm !important;
            border-collapse: collapse;
            table-layout: fixed;
            width: 20.5cm;
        }

        /* Setiap cell = 1 label slot */
        .label-td {
            padding: 0 !important;
            margin:  0 !important;
            width:   4.1cm;
            height:  2.5cm;   /* Diperbesar untuk muat barcode */
            vertical-align: top;
        }

        /* Box label sebenarnya */
        .label-box {
            width:  3.8cm;
            height: 2.3cm;
            margin: 0;
            padding: 3px 4px 2px 4px;
            display: block;
            box-sizing: border-box;
            text-align: center;
            overflow: hidden;
            border: none;
        }

        /* ID kecil di atas */
        .id-barang {
            font-size: 5.5pt;
            color: #999;
            line-height: 1;
            margin-bottom: 1px;
        }

        /* Barcode image */
        .barcode-img {
            display: block;
            width: 3.2cm;      /* Lebar tetap agar proporsional di PDF */
            height: auto;
            margin: 0 auto 1px auto;
        }

        /* Nama barang */
        .nama-barang {
            font-size: 7.5pt;
            font-weight: bold;
            line-height: 1.1;
            margin: 1px 0;
            /* Maks 2 baris */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        /* Harga */
        .harga-barang {
            font-size: 9pt;
            font-weight: bold;
            color: #111;
        }
    </style>
</head>
<body>
    <table>
        @php
            $currentSlot = 0;
        @endphp

        @for ($row = 0; $row < 8; $row++)
            <tr>
                @for ($col = 0; $col < 5; $col++)
                    @php $currentSlot++; @endphp
                    <td class="label-td">

                        @if ($currentSlot > $skipSlots && count($items) > 0)
                            @php
                                $item = $items->shift();

                                /*
                                 * Generate barcode Code128 untuk id_barang.
                                 * BarcodeHelper::img() mengembalikan <img src="data:image/png;base64,...">
                                 * yang sepenuhnya didukung DomPDF tanpa request HTTP eksternal.
                                 *
                                 * Parameter: (text, height_px, barWidth_px, style)
                                 */
                                $barcodeHtml = \App\Helpers\BarcodeHelper::img(
                                    (string) $item->id_barang,
                                    48,   // tinggi 48px → ~1.3cm di PDF
                                    2,    // lebar 1 unit bar = 2px
                                    'width:3.2cm; height:auto;'
                                );
                            @endphp

                            <div class="label-box">
                                {{-- ID kecil --}}
                                <div class="id-barang">{{ $item->id_barang }}</div>

                                {{-- Barcode --}}
                                {!! $barcodeHtml !!}

                                {{-- Nama Barang --}}
                                <div class="nama-barang">{{ $item->nama }}</div>

                                {{-- Harga --}}
                                <div class="harga-barang">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </div>
                            </div>

                        @else
                            {{-- Slot kosong (skip / padding) --}}
                            <div style="width:4.1cm; height:2.5cm;"></div>
                        @endif

                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>
