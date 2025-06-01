<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi Penitipan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');
        if (role !== 'pegawai_gudang') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai_gudang.');
            window.location.href = '/login';
        }
    </script>
    <style>
        table {
            border: 2px solid black;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .container {
            margin-top: 30px;
        }
        .product-images {
            display: flex;
            justify-content: center;
            gap: 10px; /* Jarak antar gambar */
        }
        .product-image {
            width: 80px; /* Ukuran gambar yang kecil agar tidak terlalu besar */
            height: auto;
        }
        .action-column {
            width: 250px; /* Lebar kolom action lebih besar */
        }
        .btn-group {
            display: flex; /* Menggunakan Flexbox untuk tombol */
            gap: 10px; /* Jarak antar tombol */
            justify-content: center; /* Agar tombol berada di tengah */
        }
        .btn-group button {
            flex: 1; /* Membuat tombol berukuran sama */
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <div class="container">
        <div class="mb-3">
            <a href="{{ route('showPilihTransaksi') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <h1 class="text-center">Daftar Semua Transaksi Penitipan</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <!-- Green Button to Products Page -->
        <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.show_produk') }}" class="btn btn-success">Produk</a>
        </div>

        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchTranskasiPenitipan" class="form-control" placeholder="Cari Transaksi Penitipan...">
        </div>

        <!-- Tambah Produk Button (Blue) -->
        <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.create_transaksi_penitipan') }}" class="btn btn-primary">Tambah Transaksi Penitipan</a>
        </div>

        <!-- Tabel Transaksi Penitipan -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Penitipan</th>
                    <th>Kode Produk</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Penitip</th>
                    <th>Tanggal Penitipan</th>
                    <th>Status</th>
                    <th>Tanggal Diretur</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ongoingTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->ID_PENITIPAN }}</td>
                    <td>{{ $transaction->produk->KODE_PRODUK }}</td>
                    <td>
                        <div class="product-images">
                            <img src="{{ asset('foto_produk/' . $transaction->produk->KODE_PRODUK . '_1.jpg') }}" alt="Foto Produk 1" class="product-image">
                            <img src="{{ asset('foto_produk/' . $transaction->produk->KODE_PRODUK . '_2.jpg') }}" alt="Foto Produk 2" class="product-image">
                        </div>
                    </td>
                    <td>{{ $transaction->produk->NAMA_PRODUK }}</td>
                    <td>{{ $transaction->penitip->NAMA_PENITIP ?? 'Tidak Ditemukan' }}</td>
                    <td>{{ $transaction->TANGGAL_PENITIPAN }}</td>
                    <td>{{ $transaction->STATUS_PENITIPAN }}</td>
                    <td>{{ $transaction->TANGGAL_DIAMBIL ?? 'Belum Ada'}}</td>
                    <td>
                        <div class="btn-group" style="width: 100%;">
                            <a href="{{ route('pegawai_gudang.update_transaksi_penitipan', $transaction->ID_PENITIPAN) }}" class="btn btn-warning">Update</a>

                            <form action="{{ route('pegawai_gudang.delete_transaksi_penitipan', $transaction->ID_PENITIPAN) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                            @if ($transaction->STATUS_PENITIPAN == 'Akan Diambil')
                                <form action="{{ route('pegawai_gudang.mark_as_taken', $transaction->ID_PENITIPAN) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah produk ini sudah diambil?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Diambil</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    document.getElementById('searchTranskasiPenitipan').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase(); // Convert search value to lowercase for case-insensitive search
        const table = document.querySelector('table'); // Select the table element
        const rows = table.querySelectorAll('tbody tr'); // Select all table rows in the tbody section

        rows.forEach(row => {
            let rowText = ''; // Initialize a variable to accumulate the row's text content

            // Loop through all columns (td elements) in the current row
            row.querySelectorAll('td').forEach(cell => {
                rowText += cell.textContent.toLowerCase(); // Add the text of each cell to rowText
            });

            // If any part of the row text contains the search value, display the row
            if (rowText.includes(searchValue)) {
                row.style.display = ''; // Show the row
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
    });
    </script>
</body>
</html>
