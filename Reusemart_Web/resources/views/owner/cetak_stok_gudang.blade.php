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

    <!-- Tampilkan informasi ini di awal setiap bulan -->
    <div class="nota">
        <h2>ReUse Mart</h2>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <br>
        <p class="bold underline">LAPORAN STOK GUDANG</p>
        <p>Tanggal cetak: {{ date('d F Y') }}</p>

        <!-- Tabel Laporan -->
        <table>
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>ID Penitip</th>
                    <th>Nama Penitip</th>
                    <th>Tanggal Masuk</th>
                    <th>Perpanjangan</th>
                    <th>ID Hunter</th>
                    <th>Nama Hunter</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produk as $item) <!-- Loop for each product -->
                    <tr>
                        <!-- KODE_PRODUK: Prefix with first letter of product name -->
                        <td>{{ strtoupper(substr($item->NAMA_PRODUK, 0, 1)) . $item->KODE_PRODUK }}</td>

                        <!-- NAMA_PRODUK -->
                        <td>{{ $item->NAMA_PRODUK }}</td>

                        <!-- ID_PENITIP: Prefix with 'T' -->
                        <td>{{ 'T' . $item->transaksiPenitipan->first()->ID_PENITIP }}</td>

                        <!-- NAMA_PENITIP -->
                        <td>{{ $item->transaksiPenitipan->first()->penitip->NAMA_PENITIP }}</td>

                        <!-- TANGGAL_PENITIPAN -->
                        <td>{{ \Carbon\Carbon::parse($item->transaksiPenitipan->first()->TANGGAL_PENITIPAN)->format('d F Y') }}</td>

                        <!-- STATUS_PERPANJANGAN -->
                        <td>{{ $item->transaksiPenitipan->first()->STATUS_PERPANJANGAN }}</td>

                        <!-- ID_HUNTER: Prefix with 'P' -->
                        <td>{{ $item->ID_HUNTER ? 'P' . $item->ID_HUNTER : '-' }}</td>

                        <!-- NAMA_HUNTER (Nama Pegawai) -->
                        <td>{{ $item->hunter ? $item->hunter->NAMA_PEGAWAI : '-' }}</td>

                        <!-- Harga -->
                        <td>{{ number_format($item->HARGA, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
