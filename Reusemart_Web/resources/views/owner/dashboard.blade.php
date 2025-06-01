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
                <a class="nav-link active" href="{{ url('owner/dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('owner/history_donasi') }}">History Request</a>
            </li>
        </ul>
    </div>

    <!-- Include Header -->
    @include('outer.header')

    <!-- Main Content -->
    <div class="container-main">
        <div class="dashboard-header">
            <h3>Owner Dashboard</h3>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Organisasi</th>
                        <th>Detail Request</th>
                        <th>Status Request</th>
                        <th>Nama Produk</th>
                        <th>Pilih Donasi</th> <!-- New column for the dropdown -->
                        <th>Option</th> <!-- New column for Terima and Tolak buttons -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->organisasi->NAMA_ORGANISASI ?? 'No Organization' }}</td>
                            <td>{{ $request->DETAIL_REQUEST }}</td>
                            <td>{{ $request->STATUS_REQUEST }}</td>
                            <td>{{ $request->produk->NAMA_PRODUK ?? 'No Product' }}</td>
                            <!-- Dropdown for all products -->
                            <td>
                                <select class="form-select product-select" data-id="{{ $request->ID_REQUEST }}">
                                    <option value="">Select Product</option>
                                    @foreach($allProducts as $product)
                                        <option value="{{ $product->KODE_PRODUK }}">
                                            {{ $product->NAMA_PRODUK }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- Option column with Terima and Tolak buttons -->
                            <td>
                                <button class="btn btn-success terima-btn" data-id="{{ $request->ID_REQUEST }}" disabled>
                                    <i class="fas fa-check"></i> Terima <!-- Checklist Icon -->
                                </button>
                                <button class="btn btn-danger tolak-btn" data-id="{{ $request->ID_REQUEST }}">
                                    <i class="fas fa-times"></i> Tolak <!-- Cross Icon -->
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Add custom JavaScript to handle button enabling/disabling and alerts -->
    <script>
        // Wait for the DOM to fully load before adding event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Add event listener to all product dropdowns
            const selects = document.querySelectorAll('.product-select');
            selects.forEach(select => {
                select.addEventListener('change', function () {
                    const requestId = this.getAttribute('data-id'); // Get the corresponding request ID
                    const terimaBtn = document.querySelector(`.terima-btn[data-id="${requestId}"]`);
                    if (this.value !== "") {
                        // Enable the "Terima" button if a product is selected
                        terimaBtn.disabled = false;
                    } else {
                        // Disable the "Terima" button if no product is selected
                        terimaBtn.disabled = true;
                    }
                });
            });

            // Add event listener to "Terima" buttons
            const terimaBtns = document.querySelectorAll('.terima-btn');
            terimaBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const confirmation = confirm("Yakin ingin terima donasi ini?");
                    if (!confirmation) {
                        // Prevent the action if the user cancels
                        return false;
                    }
                    // Proceed with accepting the donation
                    alert("Donasi diterima.");
                    // Add any logic to handle the accepted donation here
                });
            });

            // Add event listener to "Tolak" buttons
            const tolakBtns = document.querySelectorAll('.tolak-btn');
            tolakBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const confirmation = confirm("Yakin ingin tolak donasi ini?");
                    if (!confirmation) {
                        // Prevent the action if the user cancels
                        return false;
                    }
                    // Proceed with rejecting the donation
                    alert("Donasi ditolak.");
                    // Add any logic to handle the rejected donation here
                });
            });
        });
    </script>
</body>
</html>
