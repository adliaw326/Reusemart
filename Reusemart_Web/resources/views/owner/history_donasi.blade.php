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

        /* Container to center the table */
        .table-container {
            display: flex;
            justify-content: center;  /* Center horizontally */
            margin-top: 30px;
        }

        /* Table Styling */
        table {
            background-color: rgb(255, 255, 255);
            color: black;
            border-collapse: collapse;
            width: 90%; /* Make the table width flexible */
            table-layout: fixed; /* Make columns equal width */
        }

        th, td {
            text-align: center;
            padding: 12px;
            border: 2px solid rgb(255, 255, 255);
            word-wrap: break-word;
        }

        th {
            background-color: white;
            color: black;
        }

        .btn:disabled {
            opacity: 0.5;
        }
    </style>

    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'owner') {
            alert('Akses ditolak. Halaman ini hanya untuk owner.');
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
                <a class="nav-link " href="{{ url('owner/dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ url('owner/history_donasi') }}">History Request</a>
            </li>
        </ul>
    </div>

    <!-- Include Header -->
    @include('outer.header')

    <!-- Main Content -->
    <div class="container-main">
        <div class="dashboard-header">
            <h3>History Donasi</h3>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Organisasi</th>
                        <th>Detail Request</th>
                        <th>Status Request</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->organisasi->NAMA_ORGANISASI ?? 'No Organization' }}</td>
                            <td>{{ $request->DETAIL_REQUEST }}</td>
                            <td>{{ $request->STATUS_REQUEST }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
