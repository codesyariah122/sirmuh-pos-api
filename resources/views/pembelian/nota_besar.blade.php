<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Pembelian - {{$kode}}</title>
    {{-- @vite(['resources/css/app.css']) --}}
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
            margin-top: .1rem;
            letter-spacing: 1px;
            font-size: 11px;
        }

        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 2px;
            font-size: 10px;
        }
        table.data {
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h4 style="margin-top: 1rem;">INVOICE</h4>
    <table width="100%" style="border-collapse: collapse; margin-top: .5rem;">
        <tr>
            <td style="vertical-align: top;">
                Kepada
            </td>
            <td rowspan="6" width="30%" style="vertical-align: top;">
                @if($toko['name'] === 'CV Sangkuntala Jaya Sentosa')
                <img src="{{ public_path('storage/tokos/' . $toko['logo']) }}" alt="{{$toko['logo']}}" width="60" />
                @else
                <img src="{{ public_path('storage/tokos/' . $toko['logo']) }}" alt="{{$toko['logo']}}" width="100" />
                @endif
                <br>
                {{ $toko['name'] }}                 
                <br>
                <address>
                    {{ $toko['address'] }}
                </address>
            </td>
        </tr>

        <tr>
            <td>
                {{$pembelian->nama_supplier}}({{$pembelian->supplier}})
            </td>
        </tr>

        <tr>
            <td>
                NO INVOICE : 
                {{$pembelian->kode}}
                <br>
                {{$helpers->format_tanggal($pembelian['tanggal'])}}
            </td>
        </tr>
        <tr>
            <td>
                Jenis : {{$pembelian->po == 'True' ? 'Pembelian P.O' : 'Pembelian Langsung'}}
            </td>
        </tr>
    </table>

    <table class="data" width="100%" style="margin-top: -1.5rem;">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga Satuan</th>
                <th>Supplier</th>
                <th>Saldo Hutang</th>
                <th>Jumlah</th>
                <th>Pembayaran</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        @if($pembelian->po == "False")
        <tbody>
            @foreach ($barangs as $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td class="text-center">{{ $item->kode_barang }}</td>
                <td class="text-center">{{ $item->nama_barang }}</td>
                <td class="text-right">{{ $helpers->format_uang($item->harga_beli) }}</td>
                <td class="text-center"> {{$item->nama_supplier}} ({{$item->kode_supplier}}) </td>
                <td class="text-right"> {{$helpers->format_uang($item->saldo_hutang)}}</td>
                <td class="text-right">{{ $item->qty." ".$item->satuan }}</td>
                <td class="text-right">{{ $pembelian->po === 'True' ? 'DP Awal' : $item->visa }}</td>
                <td class="text-right">{{ $helpers->format_uang($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        @else
        <tbody>
            @foreach ($barangs as $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td class="text-center">{{ $item->kode_barang }}</td>
                <td class="text-center">{{ $item->nama_barang }}</td>
                <td class="text-right">{{ $helpers->format_uang($item->harga_beli) }}</td>
                <td class="text-center"> {{$item->nama_supplier}} ({{$item->kode_supplier}}) </td>
                <td class="text-right"> {{$helpers->format_uang($item->saldo_hutang)}}</td>
                <td class="text-right">{{ $orders ." ".$item->satuan }}</td>
                <td class="text-right">{{ $item->visa }}</td>
                <td class="text-right">{{ $helpers->format_uang($orders * $item->harga_beli) }}</td>
            </tr>
            @endforeach
            @endif
            <tfoot>
                @if($pembelian->po === 'False')
                <tr>
                    <td colspan="8" class="text-right">SubTotal</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->jumlah) }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right">Biaya Bongkar</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->biayabongkar) }}</td>
                </tr>
                @if($pembelian->visa !== 'HUTANG')
                <tr>
                    <td colspan="8" class="text-right">Grand Total Bayar</td>
                    <td class="text-right">{{ $pembelian->biayabongkar !== NULL ? $helpers->format_uang($pembelian->jumlah + $pembelian->biayabongkar) : $helpers->format_uang($pembelian->jumlah) }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="8" class="text-right">Total Bayar</td>
                    <td class="text-right">{{ $pembelian->biayabongkar !== NULL ? $helpers->format_uang($pembelian->bayar + $pembelian->biayabongkar) : $helpers->format_uang($pembelian->bayar) }}</td>
                </tr>
                @endif
                @endif


                @if($pembelian->visa === 'HUTANG')
                <tr>
                    <td colspan="8" class="text-right">
                        {{$pembelian->visa === "DP AWAL" ? "DP Awal" : "Total DP"}}
                    </td>
                    <td class="text-right">{{ $pembelian->po === 'True' ? $helpers->format_uang($pembelian->bayar) : $helpers->format_uang($pembelian->diterima) }}</td>
                </tr>
                @if($pembelian->po === "True")
                @if($pembelian->lunas == "True")
                <tr>
                    <td colspan="8" class="text-right">Sisa DP</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->hutang) }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="8" class="text-right">Masuk Hutang</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->hutang) }}</td>
                </tr>
                @endif
                @else
                <tr>
                    <td colspan="8" class="text-right">Hutang</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->hutang) }}</td>
                </tr>
                @endif
                @else

                @if($pembelian->po === 'True')
                <tr>
                    <td colspan="8" class="text-right">DP Awal</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->jumlah) }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right">Diterima</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->diterima) }}</td>
                </tr>
                @if($pembelian->lunas === "True")
                <tr>
                    <td colspan="8" class="text-right">Biaya Bongkar</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->biayabongkar) }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right">Sisa Bayar</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->bayar - $pembelian->jumlah) }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right">Grand Total Bayar</td>
                    <td class="text-right">{{ $pembelian->biayabongkar !== NULL ? $helpers->format_uang($pembelian->bayar + $pembelian->biayabongkar) : $helpers->format_uang($pembelian->bayar) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="8" class="text-right">
                        @if($pembelian->visa === "LUNAS")
                        Kembali 
                        @else
                        Sisa DP
                        @endif
                    </td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->bayar - $pembelian->diterima) }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="8" class="text-right">Dibayar</td>
                    <td class="text-right">{{ $pembelian->biayabongkar !== NULL ? $helpers->format_uang($pembelian->bayar + $pembelian->biayabongkar) : $helpers->format_uang($pembelian->bayar) }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right">Kembali</td>
                    <td class="text-right">{{ $helpers->format_uang($pembelian->diterima - $pembelian->jumlah) }}</td>
                </tr>
                @endif
                @endif
            </tfoot>
        </table>
        <table width="100%" style="margin-top: .5rem;">
            <tr>
                <td class="text-right">
                    <h4>Kasir</h4>
                    <br>
                    <span style="margin-top:-.2rem;">{{ strtoupper($pembelian->operator) }}</span>
                </td>
            </tr>
        </table>
  {{--   <table width="100%">
        <tr>
            <td>Terimakasih telah berbelanja dan sampai jumpa</td>
            <td class="text-center">
                Kasir
                <br>
                <br>
                {{ strtoupper($pembelian->operator) }}
            </td>
        </tr>
    </table> --}}
</body>
</html>
