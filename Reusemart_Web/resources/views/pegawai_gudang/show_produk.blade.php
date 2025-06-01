<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
        <h1 class="text-center">Daftar Semua Produk</h1>

        <!-- Transaksi Penitipan Button -->
        <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.show_transaksi_penitipan') }}" class="btn btn-success">Transaksi Penitipan</a>
        </div>

        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchProduk" class="form-control" placeholder="Cari Produk...">
        </div>

        <!-- Tambah Produk Button (Blue) -->
        <div class="mb-3 text-center">
            <a href="{{ route('produk.create_produk') }}" class="btn btn-primary">Tambah Produk</a>
        </div>

        <!-- Tabel Produk -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Berat (kg)</th>
                    <th>Garansi</th>
                    <th>Rating</th>
                    <th class="action-column">Action</th> <!-- Lebar kolom action lebih besar -->
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $item)
                    <tr>
                        <!-- Kolom Kode Produk dan Detail -->
                        <td>{{ $item->KODE_PRODUK }}</td>
                        <!-- Kolom Foto -->
                        <td>
                            <div class="product-images">
                                <img src="{{ asset('foto_produk/' . $item->KODE_PRODUK . '_1.jpg') }}" alt="Foto Produk 1" class="product-image">
                                <img src="{{ asset('foto_produk/' . $item->KODE_PRODUK . '_2.jpg') }}" alt="Foto Produk 2" class="product-image">
                            </div>
                        </td>
                        <td>{{ $item->NAMA_PRODUK }}</td>
                        <td>{{ $item->KATEGORI }}</td>
                        <td>Rp {{ number_format($item->HARGA, 0, ',', '.') }} </td>
                        <td>{{ number_format($item->BERAT, 2) }}</td>
                        <td>{{ $item->GARANSI ?? 'Tidak Ada' }}</td>
                        <td>{{ $item->RATING ?? 'Tidak Ada' }}</td>

                        <!-- Kolom Action -->
                        <td class="action-column">
                            <div class="btn-group" style="width: 100%;">
                                <!-- Tombol Update -->
                                <a href="{{ route('pegawai_gudang.update_produk', $item->KODE_PRODUK) }}" class="btn btn-warning">Update</a>

                                <!-- Tombol Delete -->
                                <form action="{{ route('pegawai_gudang.delete_produk', $item->KODE_PRODUK) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
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
    document.getElementById('searchProduk').addEventListener('keyup', function() {
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
