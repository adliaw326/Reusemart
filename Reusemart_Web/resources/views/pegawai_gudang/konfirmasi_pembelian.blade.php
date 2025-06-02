<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transaksi Pembelian</title>
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
    </style>
</head>
<body>
    @include('outer.header')
<div class="container">
    <h1 class="text-center">Transaksi Menunggu Konfirmasi</h1>
    <div class="mb-3 text-center">
        <a href="{{ route('pegawai_gudang.show_produk') }}" class="btn btn-primary">Produk</a>
    </div>
    <div class="mb-3 text-center">
            <a href="{{ route('pegawai_gudang.show_transaksi_penitipan') }}" class="btn btn-success">Transaksi Penitipan</a>
        </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Pembelian</th>
                <th>Total Bayar</th>
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
        <h5 class="modal-title" id="konfirmasiModalLabel">Detail Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Total Bayar:</strong> Rp <span id="modal-total-bayar"></span></p>
        <p><strong>Bukti Bayar:</strong></p>
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
            tbody.html("<tr><td colspan='3' class='text-muted'>Tidak ada transaksi.</td></tr>");
            return;
        }
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.ID_PEMBELIAN}</td>
                    <td>Rp${parseInt(item.TOTAL_BAYAR).toLocaleString('id-ID')}</td>
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
