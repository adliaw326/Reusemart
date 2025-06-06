<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transaksi Pembelian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp" />
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
            /* color : #FFF; */
        }
        .container {
            margin-top: 30px;
        }
        .action-column {
            width: 200px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-group button {
            flex: 1;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }
        body {
            /* background-color: #0b1e33; */
            /* color: #fff; */
        }
        .table thead th {
            color: #000;
        }
        .btn-add {
            background: #ffba42;
            color: #013c58;
            font-weight: bold;
        }
        .btn-add:hover {
            background: #f5a201;
            color: #fff;
        }
        .btn-warning,
        .btn-danger {
            font-size: 0.9rem;
        }
        .btn-back {
            background: #013c58;
            color: #ffba42;
            border: none;
            font-size: 1.1rem;
            padding: 6px 14px;
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn-back:hover {
            background: #ffba42;
            color: #013c58;
        }
        .search-box {
            max-width: 300px;
        }
        .search-box input {
            background: #013c58;
            color: #ffba42;
            border: 1px solid #ffba42;
        }
        .search-box input::placeholder {
            color: #ffba42;
        }
        .container-fluid {
            max-width: 100vw;
            padding-left: 15px;
            padding-right: 15px;
        }
        .table-responsive {
            max-width: 100%;
            overflow-x: auto;
        }
        table.table {
            width: 100%;
            table-layout: auto;
        }
        th:nth-child(6),
        td:nth-child(6) {
            /* kolom alamat */
            min-width: 180px;
            /* color : #FFF; */
        }        
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="align-items-center mb-3">
                <div class=" align-items-center">
                    <h2 class="mb-0">KONFIRMASI PEMBELIAN</h2>
                </div>
            </div>
        <h1 class="text-center">TRANSAKSI PEMBELIAN YANG SUDAH DIBAYAR</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ID Pembelian</th>
                    <th>STATUS</th>
                    <th class="action-column">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabel-transaksi">
                @foreach($transaksiPembelian as $transaksi)
                    <tr>
                        <td>{{ $transaksi->ID_PEMBELIAN }}</td>
                        <td>{{ $transaksi->STATUS_TRANSAKSI }}</td>
                        <td class="action-column">
                            <div class="btn-group">
                                <!-- <form action="{{ route('konfirmasiI', $transaksi->ID_PEMBELIAN) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                                </form>
                                <form action="{{ route('gagalKonfirmasiI', $transaksi->ID_PEMBELIAN) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Batalkan</button>
                                </form> -->
                                <button type="button" class="btn btn-secondary" id="btn-gagal" data-dismiss="modal">Tidak Valid</button>
                                <button type="button" class="btn btn-success" id="btn-konfirmasi">Konfirmasi Valid</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </body>
    <script>
        $('#btn-gagal').click(function() {
            const selectedTransaksiId = $(this).closest('tr').find('td:first').text();
            if (!selectedTransaksiId) return;

            $.post(`/api/transaksi-pembelian/gagal/${selectedTransaksiId}`, function(response) {
                alert(response.message);
                location.reload();
            }).fail(function() {
                alert('Gagal mengonfirmasi transaksi.');
            });
        });
        $('#btn-konfirmasi').click(function() {
            const selectedTransaksiId = $(this).closest('tr').find('td:first').text();
            if (!selectedTransaksiId) return;

            $.post(`/api/transaksi-pembelian/konfirmasi/${selectedTransaksiId}`, function(response) {
                alert(response.message);
                location.reload();
            }).fail(function() {
                alert('Gagal mengonfirmasi transaksi.');
            });
        });
        </script>