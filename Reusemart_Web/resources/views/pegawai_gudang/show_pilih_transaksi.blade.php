<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Penitipan Berlangsung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');
        if (role !== 'pegawai_gudang') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai_gudang.');
            window.location.href = '/login';
        }
    </script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
        }
        .btn-container {
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-lg {
            min-width: 250px;
        }
    </style>
</head>
<body>

    @include('outer.header')

    <main class="container py-4 d-flex flex-column align-items-center">
        <h3 class="mb-4">Menu Transaksi</h3>
        <div class="d-flex btn-container justify-content-center">
            <a href="{{ url('/pegawai_gudang/show_transaksi_penitipan') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-box"></i> Transaksi Penitipan
            </a>
            <a href="{{ url('/pegawai_gudang/show_transaksi_pembelian') }}" class="btn btn-success btn-lg">
                <i class="fas fa-shopping-cart"></i> Transaksi Pembelian
            </a>
        </div>
    </main>

    <footer>
        @include('outer.footer')
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
