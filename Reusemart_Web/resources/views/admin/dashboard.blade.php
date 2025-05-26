<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pegawai Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Cards Section */
        .card {
            margin: 20px;
            background-color: #00537a;
            color: white;
            border: none;
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            color: #f5a201;
        }

        .btn-primary {
            background-color: #f5a201;
            border-color: #f5a201;
        }

        .btn-primary:hover {
            background-color: #ffba42;
            border-color: #ffba42;
        }

        table {
            background-color: rgb(255, 255, 255);
            color: black;
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th, td {
            text-align: center;
            padding: 12px;
            border: 2px solidrgb(255, 255, 255);
            word-wrap: break-word;
        }

        th {
            background-color:rgb(0, 0, 0);
        }

        .table-container {
            margin-top: 30px;
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
                <a class="nav-link active" href="#">Dashboard</a>
            </li>
            <!-- Updated link to direct to the 'create_pegawai' page -->
            <li class="nav-item">
                <a class="nav-link" href="{{ url('pegawai/create_pegawai') }}">Tambah Pegawai</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Tambah Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Tambah Penitip</a>
            </li>
        </ul>
    </div>

    <!-- Include Header -->
    @include('outer.header')

    <!-- Main Content -->
    <div class="container-main">
        <div class="container">
            <div class="dashboard-header">
                <h1>Pegawai Dashboard</h1>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pegawai</h5>
                            <p class="card-text">{{ $pegawais->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <p class="card-text">{{ $produks->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Penitip</h5>
                            <p class="card-text">{{ $penitips->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Pegawai -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Nama Pegawai</th>
                        <th>Email Pegawai</th>
                        <th>Password Pegawai</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $pegawai)
                    <tr>
                        <td>{{ $pegawai->role }}</td>
                        <td>{{ $pegawai->nama }}</td>
                        <td>{{ $pegawai->email }}</td>
                        <td>{{ $pegawai->password }}</td>
                        <td>
                            <a href="{{ url('pegawai/update_pegawai/'.$pegawai->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tabel Produk -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Berat</th>
                        <th>Harga</th>
                        <th>Garansi</th>
                        <th>Rating</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produks as $produk)
                    <tr>
                        <td>{{ $produk->nama }}</td>
                        <td>{{ $produk->kategori }}</td>
                        <td>{{ $produk->berat }}</td>
                        <td>{{ $produk->harga }}</td>
                        <td>{{ $produk->garansi }}</td>
                        <td>{{ $produk->rating }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        <!-- Tabel Penitip -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email Penitip</th>
                    <th>Password Penitip</th>
                    <th>Nama Penitip</th>
                    <th>NIK</th>
                    <th>Rating Rata-Rata Penitip</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penitips as $penitip)
                <tr>
                    <td>{{ $penitip->email }}</td>
                    <td>{{ $penitip->password }}</td>
                    <td>{{ $penitip->nama }}</td>
                    <td>{{ $penitip->nik }}</td>
                    <td>{{ $penitip->rating }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>