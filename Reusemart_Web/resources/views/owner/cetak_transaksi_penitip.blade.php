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
        <p class="bold underline">LAPORAN TRANSAKSI PENITIP</p>
        <!-- <p>Tahun : </p> -->
        <p>ID Penitip :  T{{ $penitip->ID_PENITIP }}</p>
        <p>Nama Penitip : {{ $penitip->NAMA_PENITIP }}</p>
        <p>Bulan : {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}</p>
        <p>Tahun : {{ $tahun }}</p>
        <p>Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

        <!-- Tabel Laporan -->
        <table>
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Laku</th>
                    <th>Harga Jual Bersih(sudah dipotong Komisi)</th>
                    <th>Bonus Terjual Cepat</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalBersih = 0;
                    $totalBonus = 0;
                    $totalPendapatan = 0;
                @endphp

                @if($transaksi->isNotEmpty())
                @foreach($transaksi as $item) <!-- Loop for each product -->
                    <tr>
                        <!-- KODE_PRODUK: Prefix with first letter of product name -->
                        <td>{{ strtoupper(substr($item->produk->NAMA_PRODUK, 0, 1)) . $item->produk->KODE_PRODUK }}</td>

                        <!-- NAMA_PRODUK -->
                        <td>{{ $item->produk->NAMA_PRODUK }}</td>

                        <!-- ID_PENITIP: Prefix with 'T' -->
                        <td>{{ \Carbon\Carbon::parse($item->TANGGAL_PENITIPAN)->format('j/n/Y')  }}</td>

                        <!-- NAMA_PENITIP -->
                        <td>{{ \Carbon\Carbon::parse($item->produk->transaksiPembelian->TANGGAL_LUNAS)->format('j/n/Y')  }}</td>

                        <!-- TANGGAL_DONASI -->
                         @php

                            $tanggalPenitipan = Carbon::parse($item->TANGGAL_PENITIPAN);
                            $tanggalExpired = Carbon::parse($item->TANGGAL_EXPIRED);
                            $tanggalLunas = optional($item->produk->transaksiPembelian)->TANGGAL_LUNAS 
                                            ? Carbon::parse($item->produk->transaksiPembelian->TANGGAL_LUNAS)
                                            : null;

                            // Selisih bulan antara expired dan penitipan
                            $diffInMonths = $tanggalExpired->diffInMonths($tanggalPenitipan);

                            // Komisi berdasarkan selisih bulan
                            if ($diffInMonths > 1) {
                                $komisiReusemart = $item->produk->HARGA * 0.30;  // 30%
                            } else {
                                $komisiReusemart = $item->produk->HARGA * 0.20;  // 20%
                            }

                            // Bonus jika lunas dan kurang dari 7 hari dari penitipan
                            $bonus = 0;
                            if ($tanggalLunas) {
                                $diffInDaysLunasPenitipan = $tanggalLunas->diffInDays($tanggalPenitipan);
                                if ($diffInDaysLunasPenitipan < 7) {
                                    $bonus = $komisiReusemart * 0.10;  // 10% dari komisi
                                }
                            }

                            // Harga bersih setelah komisi
                            $hargaBersih = $item->produk->HARGA - $komisiReusemart;
                            $keuntungan = $bonus + $hargaBersih;

                            $totalBersih += $hargaBersih;
                            $totalBonus += $bonus;
                            $totalPendapatan += $keuntungan;
                        @endphp

                        <td>{{ number_format($hargaBersih, 0, ',', '.') }}</td>

                        <!-- NAMA ORGANISASI -->
                        <td>{{ number_format($bonus, 0, ',', '.') }}</td>

                        <!-- Nama penerima -->
                        <td>{{ number_format($keuntungan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="4" style="text-align: right;font-weight:bold">TOTAL</td>
                        <td>{{ number_format($totalBersih, 0, ',', '.') }}</td>
                        <td>{{ number_format($totalBonus, 0, ',', '.') }}</td>
                        <td>{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" class="text-center"> Tidak Ada Transaksi Pada Bulan dan Tahun segitu</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</body>
</html>
