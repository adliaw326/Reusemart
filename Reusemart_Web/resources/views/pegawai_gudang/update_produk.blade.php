<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Produk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'pegawai_gudang') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai gudang.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
        }
    </script>

    <style>
        table {
            border: 2px solid black; /* Tabel dengan border hitam */
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
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <div class="container mt-5">
        <h2 class="text-center">Update Produk</h2>

        <!-- Form untuk mengupdate produk -->
        <form action="{{ route('pegawai_gudang.update_produk', $produk->KODE_PRODUK) }}" method="POST" enctype="multipart/form-data" id="updateForm">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="KODE_PRODUK">Kode Produk:</label>
                <input type="number" class="form-control" id="KODE_PRODUK" name="KODE_PRODUK" value="{{ $produk->KODE_PRODUK }}" readonly>
            </div>

            <div class="form-group">
                <label for="ID_PEGAWAI">ID Pegawai:</label>
                <input type="text" class="form-control" id="ID_PEGAWAI" name="ID_PEGAWAI" value="{{ $produk->ID_PEGAWAI }}" readonly>
            </div>

            <div class="form-group">
                <label for="KATEGORI">Kategori Produk:</label>
                <select class="form-control" id="KATEGORI" name="ID_KATEGORI" required>
                    <option value="" disabled>Pilih Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->ID_KATEGORI }}" {{ $kat->ID_KATEGORI == $produk->ID_KATEGORI ? 'selected' : '' }}>
                            {{ $kat->NAMA_KATEGORI }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="NAMA_PRODUK">Nama Produk:</label>
                <input type="text" class="form-control" id="NAMA_PRODUK" name="NAMA_PRODUK" value="{{ $produk->NAMA_PRODUK }}" required>
            </div>

            <div class="form-group">
                <label for="BERAT">Berat (dalam gram):</label>
                <input type="number" class="form-control" id="BERAT" name="BERAT" value="{{ $produk->BERAT }}" min="0" required>
            </div>

            <div class="form-group">
                <label for="HARGA">Harga (IDR):</label>
                <input type="number" class="form-control" id="HARGA" name="HARGA" value="{{ $produk->HARGA }}" min="0" required>
            </div>

            <div class="form-group">
                <label for="GARANSI">Garansi (Tanggal):</label>
                <input type="date" class="form-control" id="GARANSI" name="GARANSI" value="{{ $produk->GARANSI }}"/>
            </div>

            <!-- Display existing FOTO_1 if it exists -->
            <div class="form-group">
                <label for="FOTO_1">Upload Foto Produk 1</label>
                <input type="file" class="form-control" id="FOTO_1" name="FOTO_1" accept="image/*">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
            </div>

            <!-- Display existing FOTO_2 if it exists -->
            <div class="form-group">
                <label for="FOTO_2">Upload Foto Produk 2</label>
                <input type="file" class="form-control" id="FOTO_2" name="FOTO_2" accept="image/*">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Produk</button>
        </form>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript untuk konfirmasi sebelum submit -->
    <script>
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            // Menampilkan alert konfirmasi
            var confirmUpdate = confirm('Yakin ingin update barang ini?');
            if (!confirmUpdate) {
                // Membatalkan form submit jika user menekan Cancel
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
