<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penukaran Merchandise</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        const role = localStorage.getItem('role');
        if (role !== 'cs') {
            alert('Akses ditolak. Halaman ini hanya untuk CS.');
            window.location.href = '/login';
        }

        function updateHargaPoin() {
            var selectedMerchandise = document.getElementById('ID_MERCHANDISE');
            var hargaPoin = selectedMerchandise.options[selectedMerchandise.selectedIndex].getAttribute('data-harga');
            document.getElementById('JUMLAH_PENUKARAN').value = 1;
            updateTotalHargaPoin();
        }

        function updateTotalHargaPoin() {
            var jumlahPenukaran = document.getElementById('JUMLAH_PENUKARAN').value;
            var selectedMerchandise = document.getElementById('ID_MERCHANDISE');
            var hargaPoin = selectedMerchandise.options[selectedMerchandise.selectedIndex].getAttribute('data-harga');
            var totalHargaPoin = jumlahPenukaran * hargaPoin;

            document.getElementById('JUMLAH_HARGA_POIN').value = totalHargaPoin;
        }
    </script>
</head>
<body>
    @include('outer.header')

    <div class="container">
        <h1 class="text-center my-4">Edit Penukaran Merchandise</h1>

        <form action="{{ route('penukaran.update', $penukaran->ID_PENUKARAN) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="ID_PEMBELI">Pembeli</label>
                <select class="form-control" id="ID_PEMBELI" name="ID_PEMBELI" required>
                    @foreach ($pembelis as $pembeli)
                        <option value="{{ $pembeli->ID_PEMBELI }}" {{ $penukaran->ID_PEMBELI == $pembeli->ID_PEMBELI ? 'selected' : '' }}>
                            {{ $pembeli->NAMA_PEMBELI }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ID_MERCHANDISE">Merchandise</label>
                <select class="form-control" id="ID_MERCHANDISE" name="ID_MERCHANDISE" required onchange="updateHargaPoin()">
                    @foreach ($merchandises as $merchandise)
                        <option value="{{ $merchandise->ID_MERCHANDISE }}" 
                            {{ $penukaran->ID_MERCHANDISE == $merchandise->ID_MERCHANDISE ? 'selected' : '' }} 
                            data-harga="{{ $merchandise->HARGA_POIN }}">
                            {{ $merchandise->NAMA_MERCHANDISE }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="JUMLAH_PENUKARAN">Jumlah Penukaran</label>
                <input type="number" class="form-control" id="JUMLAH_PENUKARAN" name="JUMLAH_PENUKARAN" value="{{ $penukaran->JUMLAH_PENUKARAN }}" required oninput="updateTotalHargaPoin()">
            </div>

            <div class="form-group">
                <label for="JUMLAH_HARGA_POIN">Harga Poin</label>
                <input type="number" class="form-control" id="JUMLAH_HARGA_POIN" name="JUMLAH_HARGA_POIN" value="{{ $penukaran->JUMLAH_HARGA_POIN }}" readonly>
            </div>

            <div class="form-group">
                <label for="TANGGAL_CLAIM_PENU">Tanggal Claim</label>
                <input type="date" class="form-control" id="TANGGAL_CLAIM_PENU" name="TANGGAL_CLAIM_PENU" value="{{ $penukaran->TANGGAL_CLAIM_PENU }}" required>
            </div>

            <div class="form-group">
                <label for="TANGGAL_AMBIL_MERC">Tanggal Ambil</label>
                <input type="date" class="form-control" id="TANGGAL_AMBIL_MERC" name="TANGGAL_AMBIL_MERC" value="{{ $penukaran->TANGGAL_AMBIL_MERC }}" required>
            </div>

            <button type="submit" class="btn btn-success">Update Penukaran</button>
        </form>
    </div>

    @include('outer.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
