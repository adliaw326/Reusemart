<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kategori Per Tahun</title>
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
            font-size: 13px;
        }

        .nota h2 {
            text-align: left;
            margin-bottom: 10px;
        }

        .nota p {
            margin: 3px 0;
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

        .nota table th,
        .nota table td {
            padding: 4px;
            border: 1px solid #000;
            font-size: 12px;
        }

        /* Kolom kategori kiri */
        .nota table td.kategori,
        .nota table th.kategori {
            text-align: left;
        }

        /* Sisanya tetap tengah */
        .nota table td.tengah,
        .nota table th.tengah {
            text-align: center;
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

    <div class="nota">
        <h2>ReUse Mart</h2>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <br>
        <p class="bold underline">LAPORAN PENJUALAN PER KATEGORI BARANG</p>
        <p>Tahun: {{ $tahun }}</p>
        <p>Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th class="kategori">Kategori</th>
                    <th class="tengah">Jumlah Item Terjual</th>
                    <th class="tengah">Jumlah Item Gagal Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                    <tr>
                        <td class="kategori">{{ $item['kategori'] }}</td>
                        <td class="tengah">{{ $item['jumlah_terjual'] }}</td>
                        <td class="tengah">{{ $item['jumlah_gagal_terjual'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Tidak ada data kategori</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td class="right-align"><strong>Total</strong></td>
                    <td class="tengah"><strong>{{ $total_terjual }}</strong></td>
                    <td class="tengah"><strong>{{ $total_gagal }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
