<!-- filepath: resources/views/kelola_penitip.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Penitip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const user = JSON.parse(sessionStorage.getItem('pegawai'));

            // Jika tidak ada user atau ID_ROLE bukan RL001, redirect
            if (!user || user.ID_ROLE !== 'RL001') {
                alert('Anda tidak memiliki akses ke halaman ini.');
                window.location.href = '/admin/dashboard'; // arahkan ke halaman lain, misal home atau login
            }
        });
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
        .btn-warning, .btn-danger {
            font-size: 0.9rem;
        }
        .fa-star, .fa-star-o {
            font-size: 1rem;
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
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <button class="btn-back" onclick="window.location.href='/admin/dashboard'">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <h2 class="mb-0">Kelola Penitip</h2>
            </div>
            <div class="d-flex align-items-center">
                <form class="search-box me-2" onsubmit="return false;">
                    <input type="text" id="searchPenitip" class="form-control" placeholder="Cari Penitip...">
                </form>
                <a href="/kelola_penitip/create_penitip" class="btn btn-add">
                    <i class="fa fa-plus"></i> Add Penitip
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-white">
                <thead>
                    <tr class="text-center">
                        <th>ID PENITIP</th>
                        <th>EMAIL PENITIP</th>
                        <th>NAMA PENITIP</th>
                        <th>NIK</th>
                        <th>Rating Rata-Rata Penitip</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody id="penitipTableBody">
                    <tr><td colspan="6" class="text-center">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allPenitip = [];

        document.addEventListener('DOMContentLoaded', function() {
            fetch('http://localhost:8000/api/dashboard')
                .then(response => response.json())
                .then(data => {
                    allPenitip = data.penitip || [];
                    renderPenitipTable(allPenitip);
                })
                .catch(() => {
                    document.getElementById('penitipTableBody').innerHTML = '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>';
                });

            document.getElementById('searchPenitip').addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                const filtered = allPenitip.filter(pt =>
                    (pt.EMAIL_PENITIP ?? '').toLowerCase().includes(keyword) ||
                    (pt.NAMA_PENITIP ?? '').toLowerCase().includes(keyword) ||
                    (pt.NIK ?? '').toLowerCase().includes(keyword)
                );
                renderPenitipTable(filtered);
            });
        });

        function renderPenitipTable(data) {
            let penitipRows = '';
            if (!data || data.length === 0) {
                penitipRows = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
            } else {
                data.forEach(pt => {
                    penitipRows += `<tr>
                        <td>${pt.ID_PENITIP ?? '-'}</td>
                        <td>${pt.EMAIL_PENITIP ?? '-'}</td>
                        <td>${pt.NAMA_PENITIP ?? '-'}</td>
                        <td>${pt.NIK ?? '-'}</td>
                        <td>
                            ${
                                pt.RATING_RATA_RATA_P
                                ? `<span class="text-warning">` +
                                    Array.from({length: 5}, (_, i) =>
                                        i < Math.round(pt.RATING_RATA_RATA_P)
                                        ? '<i class="fa fa-star"></i>'
                                        : '<i class="fa fa-star-o"></i>'
                                    ).join('') +
                                    `</span> <span class="text-white">${pt.RATING_RATA_RATA_P}</span>`
                                : '-'
                            }
                        </td>
                        <td class="text-center">
                            <a href="/kelola_penitip/update_penitip/${pt.ID_PENITIP}" class="btn btn-warning btn-sm me-1">
                                <i class="fa fa-edit"></i> Update
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="hapusPenitip('${pt.ID_PENITIP}')">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>`;
                });
            }
            document.getElementById('penitipTableBody').innerHTML = penitipRows;
        }

        function hapusPenitip(id) {
            console.log('ID yang dikirim:', id);
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            console.log('Meta CSRF:', csrfMeta);
            if (!id) {
                alert('ID Penitip tidak valid');
                return;
            }
            if (!csrfMeta) {
                alert('CSRF token tidak ditemukan, silakan refresh halaman.');
                return;
            }

            const csrfToken = csrfMeta.getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            if (confirm('Yakin ingin menghapus penitip ini?')) {
                fetch(`http://localhost:8000/api/penitip/${encodeURIComponent(id)}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        alert('Penitip berhasil dihapus!');
                        location.reload();
                    } else {
                        alert(res.message || 'Gagal menghapus penitip.');
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan server.');
                });
            }
        }

    </script>
</body>
</html>
