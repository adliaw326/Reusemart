<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk Expired</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .nota {
            width: 100%;
            margin: 10px auto;
            padding: 5px;
            border: 1px solid #000;
            border-radius: 5px;
        }
        .nota h2 {
            margin-bottom: 5px;
        }
        .nota p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @php use Carbon\Carbon; Carbon::setLocale('id'); @endphp

    <div class="nota">
        <h2>ReUse Mart</h2>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <br>
        <p class="underline"><strong>LAPORAN Barang yang Masa Penitipannya Sudah Habis</strong></p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>ID Penitip</th>
                    <th>Nama Penitip</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Akhir</th>
                    <th>Batas Ambil</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produkExpired as $produk)
                    <tr>
                        <td style="text-align: left">{{ $produk['kode_produk'] }}</td>
                        <td style="text-align: left">{{ $produk['nama_produk'] }}</td>
                        <td style="text-align: left">T{{ $produk['id_penitip'] }}</td>
                        <td style="text-align: left">{{ $produk['nama_penitip'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($produk['tanggal_masuk'])->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($produk['tanggal_akhir'])->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($produk['batas_ambil'])->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Tidak ada produk expired</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
