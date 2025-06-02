<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');
        if (role !== 'pegawai_gudang') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai gudang.');
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
            gap: 10px;
        }
        .product-image {
            width: 80px;
            height: auto;
        }
        .action-column {
            width: 250px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-group button {
            flex: 1;
        }
    </style>
</head>
<body>
    @include('outer.header')

    <div class="container">
        <h1 class="text-center">Daftar Semua Produk</h1>

        <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.show_transaksi_penitipan') }}" class="btn btn-success">Transaksi Penitipan</a>
        </div>

        <div class="mb-3">
            <input type="text" id="searchProduk" class="form-control" placeholder="Cari Produk...">
        </div>

        <div class="mb-3 text-center">
            <a href="{{ route('produk.create_produk') }}" class="btn btn-primary">Tambah Produk</a>
        </div>

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
                    <th class="action-column">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $item)
                    <tr>
                        <td>
                            @php
                                $nama = strtoupper($item->NAMA_PRODUK);
                                $inisial = implode('', array_map(fn($word) => $word[0], explode(' ', $nama)));
                                $kodeFormat = $inisial . preg_replace('/[^0-9]/', '', $item->KODE_PRODUK); // ambil angka dari kode produk
                            @endphp
                            {{ $kodeFormat }}
                        </td>
                        <td>
                            <div class="product-images">
                                <img src="{{ asset('foto_produk/' . $item->KODE_PRODUK . '_1.jpg') }}" alt="Foto Produk 1" class="product-image">
                                <img src="{{ asset('foto_produk/' . $item->KODE_PRODUK . '_2.jpg') }}" alt="Foto Produk 2" class="product-image">
                            </div>
                        </td>
                        <td>{{ $item->NAMA_PRODUK }}</td>
                        <td>{{ $item->KATEGORI }}</td>
                        <td>Rp {{ number_format($item->HARGA, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->BERAT, 2) }}</td>
                        <td>{{ $item->GARANSI ?? 'Tidak Ada' }}</td>
                        <td>{{ $item->RATING ?? 'Tidak Ada' }}</td>
                        <td class="action-column">
                            <div class="btn-group" style="width: 100%;">
                                <a href="{{ route('pegawai_gudang.update_produk', $item->KODE_PRODUK) }}" class="btn btn-warning">Update</a>
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

    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('searchProduk').addEventListener('keyup', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
