<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Transaksi Pembelian</title>
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
        .rating-btn {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <div class="container">
        <h2 class="my-4">History Transaksi Pembelian</h2>

        @if ($transaksiPembelian->isEmpty())
            <div class="alert alert-warning">
                Tidak ada transaksi dengan status rating "BELUM".
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th> <!-- Kolom Nama Produk -->
                        <th>ID Pembelian</th>
                        <th>Status Transaksi</th>
                        <th>Tanggal Pesan</th>
                        <th>Berikan Rating</th> <!-- Kolom untuk memberikan rating -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksiPembelian as $transaksi)
                        <tr>
                            <td>{{ $transaksi->produk->NAMA_PRODUK ?? 'Produk Tidak Ditemukan' }}</td> <!-- Menampilkan Nama Produk -->
                            <td>{{ $transaksi->ID_PEMBELIAN }}</td>
                            <td>{{ $transaksi->STATUS_TRANSAKSI }}</td>
                            <td>{{ $transaksi->TANGGAL_PESAN }}</td>
                            <td>
                                <!-- Form untuk rating, ID_PEMBELIAN disertakan -->
                                <form action="{{ route('transaksi_pembelian.rating', $transaksi->ID_PEMBELIAN) }}" method="POST" id="rating-form-{{ $transaksi->ID_PEMBELIAN }}">
                                    @csrf
                                    <!-- Tombol Rating 1-5 -->
                                    <button type="button" class="btn btn-warning rating-btn" onclick="confirmRating(1, {{ $transaksi->ID_PEMBELIAN }})">1</button>
                                    <button type="button" class="btn btn-warning rating-btn" onclick="confirmRating(2, {{ $transaksi->ID_PEMBELIAN }})">2</button>
                                    <button type="button" class="btn btn-warning rating-btn" onclick="confirmRating(3, {{ $transaksi->ID_PEMBELIAN }})">3</button>
                                    <button type="button" class="btn btn-warning rating-btn" onclick="confirmRating(4, {{ $transaksi->ID_PEMBELIAN }})">4</button>
                                    <button type="button" class="btn btn-warning rating-btn" onclick="confirmRating(5, {{ $transaksi->ID_PEMBELIAN }})">5</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Fungsi untuk konfirmasi rating
        function confirmRating(rating, idPembelian) {
            if (confirm("Yakin ingin memberikan rating " + rating + " untuk produk ini?")) {
                // Menambahkan input hidden ke form untuk rating yang dipilih
                var form = document.getElementById('rating-form-' + idPembelian);
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "rating";
                input.value = rating;
                form.appendChild(input);

                // Submit form
                form.submit();
            }
        }
    </script>
</body>
</html>
