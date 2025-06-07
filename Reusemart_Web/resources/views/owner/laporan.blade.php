<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pegawai Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add FontAwesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        
        body {
            background-color: #0b1e33;
        }

        /* Dashboard Header */
        .dashboard-header {
            background-color: #0b1e33;
            color: #ffba42;
            padding: 20px;
            text-align: center;
        }

        /* Sidebar */
        .sidebar {
            background-color: #013c58;
            color: #ffba42;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
        }
        
        .sidebar .nav-link {
            color: #ffba42;
        }
        
        .sidebar .nav-link:hover {
            color: #f5a201;
        }
        
        .sidebar .active {
            background-color: #00537a;
        }

        /* Logo Styling */
        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-container img {
            width: 200px;  /* Adjust the size of the logo */
            height: auto;
        }

        /* Main Content */
        .container-main {
            margin-left: 250px;
            padding-top: 20px;
            height: 100vh;
            overflow-y: auto;
        }

        /* Container to center the buttons */
        .buttons-container {
            margin-top: 30px;
        }

        .form-container {
            margin-top: 30px;
            text-align: center;
        }

        /* Custom Styles for Labels */
        .form-container label {
            color: white; /* Make label text white */
            font-size: 16px;
            margin-right: 10px;
        }

        .form-container select {
            padding: 5px 15px;
            margin: 0 10px;
            font-size: 16px;
        }

        .form-container button {
            background-color: #00537a;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .form-container button:hover {
            background-color: #003d56;
            color: #f5a201;
        }

        /* Button Styling */
        .btn-custom {
            background-color: #00537a;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #003d56;
            color: #f5a201;
        }

        .btn:disabled {
            opacity: 0.5;
        }

        /* Styling for button container */
        .button-item {
            margin-bottom: 15px;  /* Space between buttons */
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar position-fixed p-3 pt-5">
        <div class="logo-container">
            <!-- Replace 'logoBesar.png' with the correct image file path -->
            <img src="{{ asset('icon/logoBesar.webp') }}" alt="Barang Bekas Murah Logo">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('owner/dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('owner/history_donasi') }}">History Request</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ url('owner/laporan') }}">Laporan Perusahaan</a>
            </li>
        </ul>
    </div>

    <!-- Include Header -->
    @include('outer.header')

    <!-- Main Content -->
    <div class="container-main">
        <div class="dashboard-header">
            <h3>Laporan Perusahaan</h3>
        </div>

        <!-- Button Container for each button group -->
        <div class="buttons-container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                <!-- Penjualan Bulanan Keseluruhan Button -->
                <div class="col mb-3">
                    <a href="{{ url('/owner/cetak_penjualan_bulanan') }}" class="btn btn-custom w-100">Lihat Penjualan Bulanan Keseluruhan</a>
                </div>
                
                <!-- Laporan Komisi Bulanan per Produk Button -->
                <div class="col mb-3">
                    <a href="{{ url('/owner/cetak_komisi_bulanan') }}" class="btn btn-custom w-100">Laporan Komisi Bulanan per Produk</a>
                </div>
                
                <!-- Laporan Stok Gudang Button -->
                <div class="col mb-3">
                    <a href="{{ url('/owner/cetak_stok_gudang') }}" class="btn btn-custom w-100">Laporan Stok Gudang</a>
                </div>
            </div>
        </div>

        <!-- Another group of buttons -->
        <div class="buttons-container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                <!-- Penjualan Bulanan Keseluruhan Button -->
                <div class="col mb-3">
                    <a href="{{ url('/owner/cetak_penjualan_bulanan_pdf') }}" class="btn btn-custom w-100">Cetak Penjualan Bulanan Keseluruhan</a>
                </div>
                
                <!-- Laporan Komisi Bulanan per Produk Button -->
                <div class="col mb-3">
                    <a href="{{ url('/owner/cetak_komisi_bulanan_pdf') }}" class="btn btn-custom w-100">Cetak Laporan Komisi Bulanan per Produk</a>
                </div>
                
                <!-- Laporan Stok Gudang Button -->
                <div class="col mb-3">
                    <a href="{{ url('/owner/cetak_stok_gudang_pdf') }}" class="btn btn-custom w-100">Cetak Laporan Stok Gudang</a>
                </div>
            </div>
        </div>

        <div class="buttons-container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                <div class="col mb-3">
                    <!-- Biar rapi hehe -->
                </div>
                <div class="form-container">
                    <form action="{{ url('owner/cetak_komisi_bulanan_pdf_bulan') }}" method="GET">
                        <label for="year" style="color:white;">Pilih Tahun: </label>
                        <select name="year" id="year">
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>

                        <label for="month" style="color:white;">Pilih Bulan: </label>
                        <select name="month" id="month">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>

                        <button type="submit" class="btn btn-custom">Cetak Laporan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form to select year and month -->


    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
