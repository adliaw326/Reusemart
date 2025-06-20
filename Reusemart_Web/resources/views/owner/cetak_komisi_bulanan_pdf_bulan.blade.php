<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Komisi Bulanan</title>
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
    @foreach($transaksiPembelianByMonth as $month => $transaksi)
        <div class="nota">
            <h2>ReUse Mart</h2>
            <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
            <br>
            <p class="bold underline">LAPORAN KOMISI BULANAN</p>
            
            <!-- Menampilkan Bulan dan Tahun -->
            <p>Bulan: {{ $month }}</p>
            <p>Tanggal cetak: {{ date('d F Y') }}</p>

            <!-- Tabel Laporan -->
            <table>
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Laku</th>
                        <th>Komisi Hunter</th>
                        <th>Komisi ReUse Mart</th>
                        <th>Bonus Penitip</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalHargaJual = 0;
                        $totalKomisiHunter = 0;
                        $totalKomisiReUseMart = 0;
                        $totalBonusPenitip = 0;
                    @endphp

                    @foreach($transaksi as $transaksi)
                        @php
                            $produk = $transaksi->produk->first();
                            $tanggalMasuk = $transaksi->transaksiPenitipan->first() ? $transaksi->transaksiPenitipan->first()->TANGGAL_PENITIPAN : 'Tidak Ada Data';
                            $komisiHunter = optional($transaksi->komisi->first())->KOMISI_HUNTER ?? 0;
                            $komisiReUseMart = optional($transaksi->komisi->first())->KOMISI_REUSEMART ?? 0;
                            $bonusPenitip = optional($transaksi->komisi->first())->BONUS_PENITIP ?? 0;

                            $kodeProduk = strtoupper(substr($produk->NAMA_PRODUK, 0, 1)) . $produk->KODE_PRODUK;

                            $totalHargaJual += $produk->HARGA;
                            $totalKomisiHunter += $komisiHunter;
                            $totalKomisiReUseMart += $komisiReUseMart;
                            $totalBonusPenitip += $bonusPenitip;
                        @endphp
                        <tr>
                            <td>{{ $kodeProduk }}</td>
                            <td>{{ $produk ? $produk->NAMA_PRODUK : 'Tidak Ada Data' }}</td>
                            <td>{{ $produk ? number_format($produk->HARGA, 2) : 'Tidak Ada Data' }}</td>
                            <td>{{ $tanggalMasuk }}</td>
                            <td>{{ $transaksi->TANGGAL_LUNAS }}</td>
                            <td>{{ number_format($komisiHunter, 2) }}</td>
                            <td>{{ number_format($komisiReUseMart, 2) }}</td>
                            <td>{{ number_format($bonusPenitip, 2) }}</td>
                        </tr>
                    @endforeach

                    <!-- Baris Total -->
                    <tr>
                        <td colspan="2" class="bold">Total</td>
                        <td>{{ number_format($totalHargaJual, 2) }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($totalKomisiHunter, 2) }}</td>
                        <td>{{ number_format($totalKomisiReUseMart, 2) }}</td>
                        <td>{{ number_format($totalBonusPenitip, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>
