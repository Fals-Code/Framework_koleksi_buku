<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        body { 
            font-family: sans-serif; 
            margin: 0; 
            padding-top: 1.2cm; /* Margin atas kertas TnJ 108 */
            padding-left: 0.5cm; /* Margin kiri kertas TnJ 108 */
        }
        
        table {
            border-spacing: 2mm 1mm; /* Jarak antar kolom dan baris */
            table-layout: fixed;
            width: 100%;
        }

        .label-box {
            width: 3.8cm; /* Lebar standar TnJ 108 */
            height: 1.9cm; /* Tinggi standar TnJ 108 */
            border: 0.1pt solid #eee; /* Garis bantu, hapus jika sudah pas */
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            padding: 5px;
        }

        .empty-box { border: none; }

        .id-barang { font-size: 7pt; color: #888; }
        .nama-barang { font-size: 9pt; font-weight: bold; margin: 2px 0; display: block; }
        .harga-barang { font-size: 11pt; font-weight: bold; color: #000; }
    </style>
</head>
<body>
    <table>
        @php 
            $currentSlot = 0; 
            $totalCols = 5; 
        @endphp

        {{-- Loop untuk baris --}}
        @for ($row = 0; $row < 8; $row++)
            <tr>
                @for ($col = 0; $col < $totalCols; $col++)
                    @php $currentSlot++; @endphp
                    
                    <td class="label-box {{ $currentSlot <= $skipSlots ? 'empty-box' : '' }}">
                        @if ($currentSlot > $skipSlots && count($items) > 0)
                            @php $item = $items->shift(); @endphp
                            <div class="id-barang">{{ $item->id_barang }}</div>
                            <div class="nama-barang">{{ $item->nama }}</div>
                            <div class="harga-barang">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                        @endif
                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>