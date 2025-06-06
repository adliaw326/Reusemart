<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transaksi Pembelian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp" />
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'cs') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
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
            background-color: #0b1e33;
            color: #fff;
        }
        .table thead th {
            background: #013c58;
            color: #ffba42;
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
    <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <button class="btn-back" onclick="window.location.href='/admin/dashboard'">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <h2 class="mb-0">KONFIRMASI PEMBELIAN</h2>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn-back" onclick="window.location.href='{{ route('kelolaPenitip') }}'">
                    Kelola Penitip
                </button>
            </div>
        </div>
    <h1 class="text-center">Transaksi Menunggu Konfirmasi</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Pembelian</th>
                <th>STATUS KIRIM</th>
                <th class="action-column">Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel-transaksi"></tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-black" id="konfirmasiModalLabel">Detail Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span class="text-black" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-black"><strong>Total Bayar:</strong> Rp <span id="modal-total-bayar"></span></p>
        <p class="text-black"><strong>Bukti Bayar:</strong></p>
        <img id="modal-bukti-bayar" src="" alt="Bukti Bayar" class="img-fluid rounded border" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btn-gagal" data-dismiss="modal">Tidak Valid</button>
        <button type="button" class="btn btn-success" id="btn-konfirmasi">Konfirmasi Valid</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let selectedTransaksiId = null;

$(document).ready(function() {
    $.get('/api/transaksi-pembelian/konfirmasi', function(data) {
        const tbody = $('#tabel-transaksi');
        if (!Array.isArray(data) || data.length === 0) {
            tbody.html("<tr><td colspan='3' class='text-warning'>Tidak ada transaksi.</td></tr>");
            return;
        }
        data.forEach(item => {
            const row = `
                <tr>
                    <td class="text-white">${item.ID_PEMBELIAN}</td>
                    <td class="text-white">${item.STATUS_TRANSAKSI}</td>
                    <td class="action-column">
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="openModal(${item.ID_PEMBELIAN}, '${item.BUKTI_BAYAR}', ${item.TOTAL_BAYAR})">Lihat & Konfirmasi</button>
                        </div>
                    </td>
                </tr>`;
            tbody.append(row);
        });
    });
});

function openModal(id, buktiBayar, totalBayar) {
    selectedTransaksiId = id;
    $('#modal-total-bayar').text(parseInt(totalBayar).toLocaleString('id-ID'));
    $('#modal-bukti-bayar').attr('src', '/storage/' + buktiBayar);
    $('#konfirmasiModal').modal('show');
}

$('#btn-konfirmasi').click(function() {
    if (!selectedTransaksiId) return;

    $.post(`/api/transaksi-pembelian/konfirmasi/${selectedTransaksiId}`, function(response) {
        alert(response.message);
        location.reload();
    }).fail(function() {
        alert('Gagal mengonfirmasi transaksi.');
    });
});

$('#btn-gagal').click(function() {
    if (!selectedTransaksiId) return;

    $.post(`/api/transaksi-pembelian/gagal/${selectedTransaksiId}`, function(response) {
        alert(response.message);
        location.reload();
    }).fail(function() {
        alert('Gagal mengonfirmasi transaksi.');
    });
});
</script>
</body>    
</html>
