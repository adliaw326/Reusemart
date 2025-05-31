<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Transaksi Penitipan</title>
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
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <div class="container mt-5">
        <h1 class="text-center">Tambah Transaksi Penitipan</h1>

        <!-- Form to create a new Transaksi Penitipan -->
        <form action="{{ route('pegawai_gudang.store_transaksi_penitipan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="KODE_PRODUK">Kode Produk</label>
                <select class="form-control" id="KODE_PRODUK" name="KODE_PRODUK" required>
                    <option value="" disabled>Pilih Produk</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->KODE_PRODUK }}">{{ $item->NAMA_PRODUK }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ID_PENITIP">ID Penitip</label>
                <select class="form-control" id="ID_PENITIP" name="ID_PENITIP" required>
                    <option value="" disabled>Pilih Penitip</option>
                    @foreach ($penitip as $item)
                        <option value="{{ $item->ID_PENITIP }}">{{ $item->NAMA_PENITIP }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="TANGGAL_PENITIPAN">Tanggal Penitipan</label>
                <input type="date" class="form-control" id="TANGGAL_PENITIPAN" name="TANGGAL_PENITIPAN" required min="{{ \Carbon\Carbon::today()->toDateString() }}" onchange="updateExpiredDate()">
            </div>

            <div class="form-group">
                <label for="TANGGAL_EXPIRED">Tanggal Expired</label>
                <input type="date" class="form-control" id="TANGGAL_EXPIRED" name="TANGGAL_EXPIRED" readonly>
            </div>

            <div class="form-group">
                <label for="STATUS_PENITIPAN">Status Penitipan</label>
                <select class="form-control" id="STATUS_PENITIPAN" name="STATUS_PENITIPAN" required disabled>
                    <option value="sedang berlangsung" selected>Sedang Berlangsung</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-block" style="margin-bottom: 50px;">Tambah Transaksi</button>
        </form>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to update Tanggal Expired automatically when Tanggal Penitipan is selected
        function updateExpiredDate() {
            // Get the value of Tanggal Penitipan
            var tanggalPenitipan = document.getElementById("TANGGAL_PENITIPAN").value;

            // Create a new Date object from the selected date
            var date = new Date(tanggalPenitipan);

            // Add 30 days to the selected date
            date.setDate(date.getDate() + 30);

            // Format the date as YYYY-MM-DD
            var day = ('0' + date.getDate()).slice(-2);
            var month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are zero-based in JavaScript
            var year = date.getFullYear();

            var formattedDate = year + '-' + month + '-' + day;

            // Set the Tanggal Expired value
            document.getElementById("TANGGAL_EXPIRED").value = formattedDate;
        }
    </script>
</body>
</html>
