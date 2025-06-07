<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .nota {
            width: 600px;
            margin: 20px auto;
            padding: 2px;
            border: 1px solid #000; /* Border hitam untuk nota */
            border-radius: 5px;
            font-size: 14px;
        }

        .grafik {
            width: 600px;
            margin: 20px auto;
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
            background-color: #fff; /* Latar belakang putih untuk tabel */
        }

        .nota table th, .nota table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #000; /* Border hitam untuk tabel */
            color: #000; /* Teks berwarna hitam */
        }

        .nota .divider {
            margin-top: 10px;
            margin-bottom: 10px;
            border-top: 1px solid #000;
        }

        .nota .right-align {
            text-align: right;
        }

        canvas {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="nota">
        <h2>ReUse Mart</h2>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <br>
        <p class="bold underline">LAPORAN PENJUALAN BULANAN</p>
        <p>Tahun: 2025</p>
        <p>Tanggal cetak: {{ date('d F Y') }}</p>

        <!-- Tabel Laporan -->
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Jumlah Barang Terjual</th>
                    <th>Jumlah Penjualan Kotor</th>
                </tr>
            </thead>
            <tbody>
                {{-- Array bulan secara manual --}}
                @php
                    $bulan = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                @endphp

                {{-- Iterasi setiap bulan --}}
                @foreach($bulan as $index => $namaBulan)
                    @php
                        // Memeriksa apakah ada data untuk bulan ini
                        $dataBulan = $penjualan->where('bulan', $index + 1)->first();
                        // Jika tidak ada data, maka tampilkan 0
                        $jumlahBarangTerjual = $dataBulan ? $dataBulan->jumlah_barang_terjual : 0;
                        $jumlahPenjualanKotor = $dataBulan ? number_format($dataBulan->jumlah_penjualan_kotor, 2) : '0.00';
                    @endphp
                    <tr>
                        <td>{{ $namaBulan }}</td>
                        <td>{{ $jumlahBarangTerjual }}</td>
                        <td>{{ $jumlahPenjualanKotor }}</td>
                    </tr>
                @endforeach
                {{-- Baris Total --}}
                <tr>
                    <td class="bold" colspan="2" style="text-align: right;">Total</td>
                    <td class="bold">{{ number_format($penjualan->sum('jumlah_penjualan_kotor'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="grafik">
        <canvas id="penjualanChart" width="600" height="400"></canvas>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk grafik
        const bulan = @json($bulan); // Mengambil bulan dari array PHP
        const penjualanData = @json($penjualan); // Mengambil data penjualan

        // Menyiapkan array untuk jumlah penjualan kotor
        const jumlahPenjualanKotor = bulan.map((month, index) => {
            // Jika data bulan tersedia, ambil jumlah penjualan kotor
            const dataBulan = penjualanData.find(item => item.bulan === index + 1);
            return dataBulan ? dataBulan.jumlah_penjualan_kotor : 0;
        });

        // Membuat grafik batang menggunakan Chart.js
        const ctx = document.getElementById('penjualanChart').getContext('2d');
        const penjualanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bulan, // Label bulan
                datasets: [{
                    label: 'Jumlah Penjualan Kotor',
                    data: jumlahPenjualanKotor, // Data jumlah penjualan kotor per bulan
                    backgroundColor: '#6a5acd', // Warna batang grafik
                    borderColor: '#4b0082', // Warna border batang
                    borderWidth: 3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
