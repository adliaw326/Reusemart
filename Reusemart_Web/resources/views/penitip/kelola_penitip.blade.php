<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Kelola Penitip</title>
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
        }
        th,
        td {
            white-space: nowrap;
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
                <h2 class="mb-0">Kelola Penitip</h2>
            </div>
            <div class="d-flex align-items-center">
                <form class="search-box me-2" onsubmit="return false;">
                    <input type="text" id="searchPenitip" class="form-control" placeholder="Cari Penitip..." />
                </form>
                <a href="/create_penitip" class="btn btn-add">
                    <i class="fa fa-plus"></i> Add Penitip
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-white">
                <thead>
                    <tr class="text-center">
                        <th>ID PENITIP</th>
                        <th>EMAIL</th>
                        <th>NAMA</th>
                        <th>NIK</th>
                        <th>No Telepon</th>
                        <th>ALAMAT DEFAULT</th>
                        <th>RATING</th>
                        <th>SALDO</th>
                        <th>POIN</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody id="penitipTableBody">
                    <tr><td colspan="10" class="text-center">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data penitip ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('penitipTableBody');
            const searchInput = document.getElementById('searchPenitip');

            let debounceTimeout;

            function fetchPenitipData(query = '') {
                const token = localStorage.getItem('token');
                const url = query
                    ? `/api/penitip/search?q=${encodeURIComponent(query)}`
                    : `/api/penitip`;

                tableBody.innerHTML = '<tr><td colspan="10" class="text-center">Loading...</td></tr>';

                fetch(url, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                    },
                })
                    .then(response => {
                        if (!response.ok) throw new Error('HTTP error ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        tableBody.innerHTML = '';
                        if (data.length === 0) {
                            tableBody.innerHTML = '<tr><td colspan="10" class="text-center">Data tidak ditemukan</td></tr>';
                            return;
                        }
                        data.forEach(p => {
                            const alamat = p.alamat_default ? p.alamat_default.LOKASI : '-';
                            const row = `
                                <tr class="text-center">
                                    <td>${p.ID_PENITIP}</td>
                                    <td>${p.EMAIL_PENITIP}</td>
                                    <td>${p.NAMA_PENITIP}</td>
                                    <td>${p.NIK}</td>
                                    <td>${p.NO_TELP_PENITIP}</td>
                                    <td>${alamat}</td>
                                    <td>${p.RATING_RATA_RATA_P ?? '-'}</td>
                                    <td>${p.SALDO_PENITIP ? 'Rp' + p.SALDO_PENITIP.toLocaleString('id-ID') : '-'}</td>
                                    <td>${p.POIN_PENITIP || '-'}</td>
                                    <td>
                                        <a href="/update_penitip/${p.ID_PENITIP.replace(/^T/, '')}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <button
                                            class="btn btn-danger btn-sm btn-delete"
                                            data-id="${p.ID_PENITIP.replace(/^T/, '')}"
                                        >
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    })
                    .catch(error => {
                        console.error('Gagal memuat data:', error);
                        tableBody.innerHTML = '<tr><td colspan="10" class="text-center text-danger">Terjadi kesalahan saat mengambil data</td></tr>';
                    });
            }

            // Initial load
            fetchPenitipData();

            // Re-fetch with debounce on search
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimeout);
                const query = this.value.trim();
                debounceTimeout = setTimeout(() => {
                    fetchPenitipData(query);
                }, 500); // delay 500ms
            });

            let deleteId = null;
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // Event delegation untuk tombol hapus
            tableBody.addEventListener('click', function (e) {
                if (e.target.closest('.btn-delete')) {
                    deleteId = e.target.closest('.btn-delete').dataset.id;
                    confirmDeleteModal.show();
                }
            });

            // Saat user konfirmasi hapus
            confirmDeleteBtn.addEventListener('click', function () {
                const token = localStorage.getItem('token');
                if (!deleteId) return;

                fetch(`/api/penitip/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                    },
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Gagal menghapus data');
                        return res.json();
                    })
                    .then(data => {
                        confirmDeleteModal.hide();
                        alert(data.message || 'Data berhasil dihapus');
                        fetchPenitipData(); // refresh data tabel
                    })
                    .catch(err => {
                        confirmDeleteModal.hide();
                        alert('Gagal menghapus data');
                        console.error(err);
                    });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
