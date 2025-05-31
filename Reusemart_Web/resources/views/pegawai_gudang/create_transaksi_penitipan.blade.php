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

        <form action="{{ route('pegawai_gudang.store_transaksi_penitipan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="ID_PEGAWAI">ID Pegawai</label>
                <input type="text" class="form-control" id="ID_PEGAWAI" name="ID_PEGAWAI" value="6" readonly>
            </div>

            <div class="form-group">
                <label for="ID_PENITIPAN">ID Penitipan</label>
                <input type="text" class="form-control" id="ID_PENITIPAN" name="ID_PENITIPAN" value="{{ $newIDPenitipan }}" readonly>
            </div>

            <div class="form-group">
                <label for="KODE_PRODUK">Nama Produk</label>
                <select class="form-control" id="KODE_PRODUK" name="KODE_PRODUK" required onchange="updateProductID()">
                    <option value="" disabled>Pilih Produk</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->KODE_PRODUK }}">{{ $item->NAMA_PRODUK }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="KODE_PRODUK">Kode Produk</label>
                <input type="text" class="form-control" id="productID" name="productID" readonly>
            </div>

            <div class="form-group">
                <label for="ID_PENITIP">Nama Penitip</label>
                <select class="form-control" id="ID_PENITIP" name="ID_PENITIP" required onchange="updatePenitipID()">
                    <option value="" disabled>Pilih Penitip</option>
                    @foreach ($penitip as $item)
                        <option value="{{ $item->ID_PENITIP }}">{{ $item->NAMA_PENITIP }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ID_PENITIP">ID Penitip</label>
                <input type="text" class="form-control" id="penitipID" name="penitipID" readonly>
            </div>

            <div class="form-group">
                <label for="TANGGAL_PENITIPAN">Tanggal Penitipan</label>
                <input type="date" class="form-control" id="TANGGAL_PENITIPAN" name="TANGGAL_PENITIPAN" required min="{{ \Carbon\Carbon::today()->toDateString() }}" onchange="updateExpiredDate()">
            </div>

            <div class="form-group">
                <label for="STATUS_PENITIPAN">Status Penitipan</label>
                <input type="text" class="form-control" id="STATUS_PENITIPAN" name="STATUS_PENITIPAN" value="sedang berlangsung" readonly>
            </div>

            <div class="form-group">
                <label for="TANGGAL_EXPIRED">Tanggal Expired</label>
                <input type="date" class="form-control" id="TANGGAL_EXPIRED" name="TANGGAL_EXPIRED" readonly>
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
        // Function to update Kode Produk when a product is selected
        function updateProductID() {
            var productSelect = document.getElementById("KODE_PRODUK");
            var productID = productSelect.options[productSelect.selectedIndex].value;
            document.getElementById("productID").value = productID;
        }

        // Function to update ID Penitip when a penitip is selected
        function updatePenitipID() {
            var penitipSelect = document.getElementById("ID_PENITIP");
            var penitipID = penitipSelect.options[penitipSelect.selectedIndex].value;
            document.getElementById("penitipID").value = penitipID;
        }

        // Function to update Tanggal Expired automatically when Tanggal Penitipan is selected
        function updateExpiredDate() {
            var tanggalPenitipan = document.getElementById("TANGGAL_PENITIPAN").value;
            
            // Check if the date is valid
            if (!tanggalPenitipan) {
                document.getElementById("TANGGAL_EXPIRED").value = ''; // Clear expiration date if no date selected
                return;
            }

            var date = new Date(tanggalPenitipan);
            
            // Check if the date object is valid
            if (isNaN(date.getTime())) {
                alert('Invalid date selected');
                return;
            }

            // Add 30 days to the selected date
            date.setDate(date.getDate() + 30);

            var day = ('0' + date.getDate()).slice(-2);
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();

            var formattedDate = year + '-' + month + '-' + day;

            document.getElementById("TANGGAL_EXPIRED").value = formattedDate;
        }
    </script>
</body>
</html>
