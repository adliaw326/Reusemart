<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Penukaran Merchandise</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');
        if (role !== 'cs') {
            alert('Akses ditolak. Halaman ini hanya untuk CS.');
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
            margin-bottom: 50px;
        }
        .action-column {
            width: 150px;
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
        <h1 class="text-center my-4">Semua Penukaran Merchandise</h1>

        <div class="mb-3">
            <input type="text" id="searchPenukaran" class="form-control" placeholder="Cari Penukaran Merchandise...">
        </div>

        <!-- Filter Buttons -->
        <div class="btn-group mb-3">
            <a href="{{ route('penukaran.show') }}" class="btn btn-secondary">Tampil Semua</a>
            <a href="{{ route('penukaran.sudahDiambil') }}" class="btn btn-success">Tampil Sudah Diambil</a>
            <a href="{{ route('penukaran.belumDiambil') }}" class="btn btn-warning">Tampil Belum Diambil</a>
        </div>

        <!-- Tabel Penukaran -->
        <table>
            <thead>
                <tr>
                    <th>ID Penukaran</th>
                    <th>Pembeli</th>
                    <th>Merchandise</th>
                    <th>Jumlah Penukaran</th>
                    <th>Harga Poin</th>
                    <th>Tanggal Claim</th>
                    <th>Tanggal Ambil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penukarans as $penukaran)
                    <tr>
                        <td>{{ $penukaran->ID_PENUKARAN }}</td>
                        <td>{{ $penukaran->pembeli->NAMA_PEMBELI }}</td>
                        <td>{{ $penukaran->merchandise->NAMA_MERCHANDISE }}</td>
                        <td>{{ $penukaran->JUMLAH_PENUKARAN }}</td>
                        <td>{{ $penukaran->JUMLAH_HARGA_POIN }}</td>
                        <td>{{ $penukaran->TANGGAL_CLAIM_PENU ?? 'Belum diambil' }}</td>
                        <td>{{ $penukaran->TANGGAL_AMBIL_MERC ?? 'Belum diambil' }}</td>
                        <td class="action-column">
                            <div class="btn-group">
                                <a href="{{ route('penukaran.edit', $penukaran->ID_PENUKARAN) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('penukaran.destroy', $penukaran->ID_PENUKARAN) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penukaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    @include('outer.footer')

    <script>
    document.getElementById('searchPenukaran').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let rowText = '';
            row.querySelectorAll('td').forEach(cell => {
                rowText += cell.textContent.toLowerCase();
            });
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
