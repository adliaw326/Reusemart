<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi Penitipan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');
        if (role !== 'pegawai_gudang') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai_gudang.');
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
        .btn-green {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    @include('outer.header')

    <div class="container">
        <div class="mb-3">
            <a href="{{ route('showPilihTransaksi') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <h1 class="text-center">Daftar Semua Transaksi Penitipan</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.show_produk') }}" class="btn btn-success">Produk</a>
        </div>
        
        <!-- <div class="mb-3 text-center">
            <a href="{{ route('konfirmasi_pembelian') }}" class="btn btn-info">Konfirmasi Transaksi Pembelian</a>
        </div> -->

        <div class="mb-3">
            <input type="text" id="searchTranskasiPenitipan" class="form-control" placeholder="Cari Transaksi Penitipan...">
        </div>

        <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.create_transaksi_penitipan') }}" class="btn btn-primary">Tambah Transaksi Penitipan</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Penitipan</th>
                    <th>Kode Produk</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Penitip</th>
                    <th>Tanggal Penitipan</th>
                    <th>Status</th>
                    <th>Tanggal Diretur</th>
                    <th>Action</th>
                    <th>Cetak Nota</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ongoingTransactions as $transaction)
                <tr>
                    <td>{{ 'T' . $transaction->ID_PENITIPAN }}</td>
                    <td>
                        @php
                            $namaProduk = $transaction->produk->NAMA_PRODUK;
                            $kodeProduk = $transaction->produk->KODE_PRODUK;
                            $inisial = '';
                            foreach (explode(' ', $namaProduk) as $kata) {
                                $inisial .= strtoupper(substr($kata, 0, 1));
                            }
                            $kodeCustom = $inisial . $kodeProduk;
                        @endphp
                        {{ $kodeCustom }}
                    </td>
                    <td>
                        <div class="product-images">
                            <img src="{{ asset('foto_produk/' . $transaction->produk->KODE_PRODUK . '_1.jpg') }}" alt="Foto Produk 1" class="product-image">
                            <img src="{{ asset('foto_produk/' . $transaction->produk->KODE_PRODUK . '_2.jpg') }}" alt="Foto Produk 2" class="product-image">
                        </div>
                    </td>
                    <td>{{ $transaction->produk->NAMA_PRODUK }}</td>
                    <td>{{ $transaction->penitip->NAMA_PENITIP ?? 'Tidak Ditemukan' }}</td>
                    <td>{{ $transaction->TANGGAL_PENITIPAN }}</td>
                    <td>{{ $transaction->STATUS_PENITIPAN }}</td>
                    <td>{{ $transaction->TANGGAL_DIAMBIL ?? 'Belum Ada'}}</td>
                    <td>
                        <div class="btn-group" style="width: 100%;">
                            <a href="{{ route('pegawai_gudang.update_transaksi_penitipan', $transaction->ID_PENITIPAN) }}" class="btn btn-warning">Update</a>
                            <form action="{{ route('pegawai_gudang.delete_transaksi_penitipan', $transaction->ID_PENITIPAN) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            @if ($transaction->STATUS_PENITIPAN == 'Akan Diambil')
                                <form action="{{ route('pegawai_gudang.mark_as_taken', $transaction->ID_PENITIPAN) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah produk ini sudah diambil?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Diambil</button>
                                </form>
                            @endif
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('pegawai_gudang.print_nota', $transaction->ID_PENITIPAN) }}" class="btn btn-green">Cetak Nota</a>
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
    document.getElementById('searchTranskasiPenitipan').addEventListener('keyup', function() {
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
</body>
</html>
