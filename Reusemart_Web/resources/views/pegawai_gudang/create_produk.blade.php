<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Produk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <div class="container mt-5">
        <h2 class="text-center">Create Produk</h2>

        <!-- Form untuk membuat produk baru -->
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="KODE_PRODUK">Kode Produk:</label>
                <input type="number" class="form-control" id="KODE_PRODUK" name="KODE_PRODUK" value="{{ $newKodeProduk }}" readonly>
            </div>

            <div class="form-group">
                <label for="ID_PEGAWAI">ID Pegawai:</label>
                <input type="text" class="form-control" id="ID_PEGAWAI" name="ID_PEGAWAI" value="6" readonly>
            </div>

            <div class="form-group">
                <label for="KATEGORI">Kategori Produk:</label>
                <select class="form-control" id="KATEGORI" name="ID_KATEGORI" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->ID_KATEGORI }}">{{ $kat->NAMA_KATEGORI }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="NAMA_PRODUK">Nama Produk:</label>
                <input type="text" class="form-control" id="NAMA_PRODUK" name="NAMA_PRODUK" required>
            </div>

            <div class="form-group">
                <label for="BERAT">Berat (dalam gram):</label>
                <input type="number" class="form-control" id="BERAT" name="BERAT" min="0" required>
            </div>

            <div class="form-group">
                <label for="HARGA">Harga (IDR):</label>
                <input type="number" class="form-control" id="HARGA" name="HARGA" min="0" required>
            </div>

            <div class="form-group">
                <label for="GARANSI">Garansi (Tanggal):</label>
                <input type="date" class="form-control" id="GARANSI" name="GARANSI">
            </div>

            <!-- Form input file untuk mengupload dua foto -->
            <div class="form-group">
                <label for="FOTO_1">Upload Foto Produk 1:</label>
                <input type="file" class="form-control" id="FOTO_1" name="FOTO_1" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="FOTO_2">Upload Foto Produk 2:</label>
                <input type="file" class="form-control" id="FOTO_2" name="FOTO_2" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Produk</button>
        </form>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
