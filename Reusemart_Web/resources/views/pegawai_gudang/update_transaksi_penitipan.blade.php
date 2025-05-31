<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Transaksi Penitipan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <div class="container mt-5">
        <h1 class="text-center">Update Transaksi Penitipan</h1>

        <!-- Form to update an existing Transaksi Penitipan -->
        <form action="{{ route('pegawai_gudang.update_transaksi_penitipan', $transaksiPenitipan->ID_PENITIPAN) }}" method="POST" enctype="multipart/form-data">
            
            @csrf
            @method('PUT') <!-- Use PUT method to indicate it's an update request -->

            <div class="form-group">
                <label for="ID_PEGAWAI">ID Pegawai</label>
                <input type="text" class="form-control" id="ID_PEGAWAI" name="ID_PEGAWAI" value="{{ $transaksiPenitipan->ID_PEGAWAI }}" readonly>
            </div>

            <div class="form-group">
                <label for="ID_PENITIPAN">ID Penitipan</label>
                <input type="text" class="form-control" id="ID_PENITIPAN" name="ID_PENITIPAN" value="{{ $transaksiPenitipan->ID_PENITIPAN }}" readonly>
            </div>

            <div class="form-group">
                <label for="KODE_PRODUK">Nama Produk</label>
                <select class="form-control" id="KODE_PRODUK" name="KODE_PRODUK" required onchange="updateProductID()">
                    <option value="" disabled>Pilih Produk</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->KODE_PRODUK }}" {{ $transaksiPenitipan->KODE_PRODUK == $item->KODE_PRODUK ? 'selected' : '' }}>
                            {{ $item->NAMA_PRODUK }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="KODE_PRODUK">Kode Produk</label>
                <input type="text" class="form-control" id="productID" name="productID" value="{{ $transaksiPenitipan->KODE_PRODUK }}" readonly>
            </div>

            <div class="form-group">
                <label for="ID_PENITIP">Nama Penitip</label>
                <select class="form-control" id="ID_PENITIP" name="ID_PENITIP" required onchange="updatePenitipID()">
                    <option value="" disabled>Pilih Penitip</option>
                    @foreach ($penitip as $item)
                        <option value="{{ $item->ID_PENITIP }}" {{ $transaksiPenitipan->ID_PENITIP == $item->ID_PENITIP ? 'selected' : '' }}>
                            {{ $item->NAMA_PENITIP }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ID_PENITIP">ID Penitip</label>
                <input type="text" class="form-control" id="penitipID" name="penitipID" value="{{ $transaksiPenitipan->ID_PENITIP }}" readonly>
            </div>

            <div class="form-group">
                <label for="TANGGAL_PENITIPAN">Tanggal Penitipan</label>
                <input type="date" class="form-control" id="TANGGAL_PENITIPAN" name="TANGGAL_PENITIPAN" required value="{{ $transaksiPenitipan->TANGGAL_PENITIPAN }}" onchange="updateExpiredDate()">
            </div>

            <div class="form-group">
                <label for="STATUS_PENITIPAN">Status Penitipan</label>
                <input type="text" class="form-control" id="STATUS_PENITIPAN" name="STATUS_PENITIPAN" value="{{ $transaksiPenitipan->STATUS_PENITIPAN }}" readonly>
            </div>

            <div class="form-group">
                <label for="TANGGAL_EXPIRED">Tanggal Expired</label>
                <input type="date" class="form-control" id="TANGGAL_EXPIRED" name="TANGGAL_EXPIRED" value="{{ $transaksiPenitipan->TANGGAL_EXPIRED }}" readonly>
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

            <button type="submit" class="btn btn-success btn-block" style="margin-bottom: 50px;">Update Transaksi</button>
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
