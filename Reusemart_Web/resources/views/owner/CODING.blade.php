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
        <p class="bold underline">LAPORAN DONASI BARANG DENGAN HUNTER</p>
        <!-- <p>Tahun : </p> -->
        <p>Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>


        <!-- Tabel Laporan -->
        <table>
            <thead>
                <tr>
                    <th>ID DONASI</th>
                    <th>KODE BARANG</th>
                    <th>NAMA BARANG</th>
                    <th>NAMA PENERIMA</th>
                    <th>NAMA HUNTER</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donasi as $item) <!-- Loop for each product -->
                    <tr>
                        <!-- KODE_PRODUK: Prefix with first letter of product name -->
                        <td>{{ strtoupper('D') . $item->ID_DONASI }}</td>

                        <!-- NAMA_PRODUK -->
                        <td>{{ $item->KODE_PRODUK }}</td>

                        <!-- ID_PENITIP: Prefix with 'T' -->
                        <td>{{ $item->produk->NAMA_PRODUK }}</td>

                        <!-- NAMA_PENITIP -->
                        <td>{{ $item->NAMA_PENERIMA}}</td>

                        <td>{{ $item->produk->hunter->NAMA_PEGAWAI}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
