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

    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'admin') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
        }
    </script>
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
                <a class="nav-link" href="{{ url('pegawai/create') }}">Tambah Pegawai</a>
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
                            <p class="card-text">{{ $pegawai->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" id="searchPegawai" class="form-control" placeholder="Cari Nama atau Email Pegawai...">
            </div>


            <!-- Tabel Pegawai -->
            <table class="table table-bordered" id="tablePegawai">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Nama Pegawai</th>
                        <th>Email Pegawai</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $p)
                    <tr>
                        <td>{{ $p->role ? $p->role->NAMA_ROLE : '-' }}</td>
                        <td>{{ $p->NAMA_PEGAWAI }}</td>
                        <td>{{ $p->EMAIL_PEGAWAI }}</td>
                        <td>
                            <a href="{{ url('pegawai/update/'.$p->ID_PEGAWAI) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ url('pegawai/delete/'.$p->ID_PEGAWAI) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.getElementById('searchPegawai').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('tablePegawai');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const nama = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();

            if (nama.includes(searchValue) || email.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>