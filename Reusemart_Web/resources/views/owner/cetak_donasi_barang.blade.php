<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Gudang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .nota {
            width: 100%;
            margin: 20px auto;
            padding: 2px;
            border: 1px solid #000;
            border-radius: 5px;
            font-size: 14px;
        }

        .nota h2 {
            text-align: left;
            margin-bottom: 10px;
        }

        .nota p {
            margin: 5px 0;
        }

        .nota .bold {
            font-weight: bold;
        }

        .nota .underline {
            text-decoration: underline;
        }

        .nota table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            background-color: #fff;
        }

        .nota table th, .nota table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
        }

        .nota .right-align {
            text-align: right;
        }
    </style>
</head>
<body>
    @php
    use Carbon\Carbon;
    Carbon::setLocale('id');
    @endphp

    <!-- Tampilkan informasi ini di awal setiap bulan -->
    <div class="nota">
        <h2>ReUse Mart</h2>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <br>
        <p class="bold underline">LAPORAN Donasi Barang</p>
        <p>Tahun : {{ $tahun }}</p>
        <p>Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

        <!-- Tabel Laporan -->
        <table>
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>ID Penitip</th>
                    <th>Nama Penitip</th>
                    <th>Tanggal Donasi</th>
                    <th>Organisasi</th>
                    <th>Nama Penerima</th>
                </tr>
            </thead>
            <tbody>
                @if($produk->isNotEmpty())
                @foreach($produk as $item) <!-- Loop for each product -->
                    <tr>
                        <!-- KODE_PRODUK: Prefix with first letter of product name -->
                        <td>{{ strtoupper(substr($item->NAMA_PRODUK, 0, 1)) . $item->KODE_PRODUK }}</td>

                        <!-- NAMA_PRODUK -->
                        <td>{{ $item->NAMA_PRODUK }}</td>

                        <!-- ID_PENITIP: Prefix with 'T' -->
                        <td>{{ 'T' . $item->transaksiPenitipan->ID_PENITIP }}</td>

                        <!-- NAMA_PENITIP -->
                        <td>{{ $item->transaksiPenitipan->penitip->NAMA_PENITIP }}</td>

                        <!-- TANGGAL_DONASI -->
                        <td>{{ \Carbon\Carbon::parse($item->donasi->TANGGAL_DONASI)->format('j/n/Y') }}</td>

                        <!-- NAMA ORGANISASI -->
                        <td>{{ $item->donasi->organisasi->NAMA_ORGANISASI}}</td>

                        <!-- Nama penerima -->
                        <td>{{ $item->donasi->NAMA_PENERIMA}}</td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada Transaksi</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</body>
</html>
