<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Penitipan Berlangsung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
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
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
        }
        .btn-container {
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-lg {
            min-width: 250px;
        }
        td {
            white-space: nowrap;
        }
    </style>
</head>
<body>

    @include('outer.header')

    <main class="container py-4 d-flex flex-column align-items-center">
        <div class="w-100 mb-3 d-flex justify-content-start">
            <a href="{{ route('showPilihTransaksi') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <h2 class="mb-4">Transaksi Pembelian - Disiapkan</h2>
        <table class="table table-bordered" id="transaksiTable">
            <thead>
                <tr>
                    <th>ID Pembelian</th>
                    <th>Pembeli</th>
                    <th>Kurir</th>
                    <th>Status Transaksi</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Lunas</th>
                    <th>Status Pengiriman</th>
                    <th>Total Bayar</th>
                    <th>Poin Diskon</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="10" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </main>
    <main class="container py-4 d-flex flex-column align-items-center">
        <h2 class="mb-4">Transaksi Pembelian - Dikirim / Diambil</h2>
        <table class="table table-bordered" id="transaksiTable">
            <thead>
                <tr>
                    <th>ID Pembelian</th>
                    <th>Pembeli</th>
                    <th>Kurir</th>
                    <th>Status Transaksi</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Lunas</th>
                    <th>Tanggal Kirim</th>
                    <th>Tanggal Ambil</th>
                    <th>Status Pengiriman</th>
                    <th>Total Bayar</th>
                    <th>Poin Diskon</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody id="tableBody2">
                <tr><td colspan="12" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </main>
    <main class="container py-4 d-flex flex-column align-items-center">
        <h2 class="mb-4">Transaksi Pembelian - Selesai / Hangus / Dibatalkan</h2>
        <table class="table table-bordered" id="transaksiTable">
            <thead>
                <tr>
                    <th>ID Pembelian</th>
                    <th>Pembeli</th>
                    <th>Kurir</th>
                    <th>Status Transaksi</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Lunas</th>
                    <th>Tanggal Kirim</th>
                    <th>Tanggal Sampai</th>
                    <th>Tanggal Ambil</th>
                    <th>Status Pengiriman</th>
                    <th>Total Bayar</th>
                    <th>Poin Diskon</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody id="tableBody3">
                <tr><td colspan="13" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </main>
    <div class="modal fade" id="kirimModal" tabindex="-1" aria-labelledby="kirimModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="kirimForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kirimModalLabel">Pilih Pegawai Pengirim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalIdPembelian">
                <input type="hidden" id="modalStatusPengiriman">
                <div class="mb-3" id="pegawaiSelectGroup">
                <label for="pegawaiSelect" class="form-label">Nama Pegawai</label>
                <select class="form-select" id="pegawaiSelect" required></select>
                </div>
                <div class="mb-3" id="tanggalPickupGroup" style="display: none;">
                <label for="tanggalPickup" class="form-label">Tanggal Pengambilan</label>
                <input type="date" class="form-control" id="tanggalPickup">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">OK</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailBody">
                    <!-- Konten produk akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>
    <footer>
        @include('outer.footer')
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const userId = localStorage.getItem('userId');
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('role');

            if (!userId || !token || role !== 'pegawai_gudang') {
                alert('Anda harus login sebagai pegawai gudang untuk melihat halaman ini.');
                window.location.href = '/login';
                return;
            }

            const tableBody = document.getElementById('tableBody');

            try {
                const response = await fetch('/api/transaksi-pembelian/disiapkan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Gagal mengambil data transaksi: ' + (errorData.error || 'Error tidak diketahui'));
                    return;
                }

                const data = await response.json();
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="10" class="text-center">Tidak ada transaksi.</td></tr>';
                    return;
                }

                data.forEach(item => {
                    const row = `<tr>
                        <td>TPB${item.ID_PEMBELIAN}</td>
                        <td>${item.pembeli.NAMA_PEMBELI}</td>
                        <td>${item.pegawai ? item.pegawai.NAMA_PEGAWAI : 'Belum Ada'}</td>
                        <td>${item.STATUS_TRANSAKSI}</td>
                        <td>${item.TANGGAL_PESAN}</td>
                        <td>${item.TANGGAL_LUNAS}</td>
                        <td>${item.STATUS_PENGIRIMAN}</td>
                        <td>Rp ${item.TOTAL_BAYAR.toLocaleString()}</td>
                        <td>${item.POIN_DISKON}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="showDetail(${item.ID_PEMBELIAN})">Detail</button>
                            ${
                                item.STATUS_TRANSAKSI === 'Disiapkan'
                                ? `<button class="btn btn-primary btn-sm" onclick="openModal(${item.ID_PEMBELIAN}, '${item.STATUS_PENGIRIMAN}')">Kirim</button>`
                                : ''
                            }
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });

            } catch (error) {
                console.error('Error:', error);
                tableBody.innerHTML =
                    '<tr><td colspan="11" class="text-center text-danger">Gagal memuat data.</td></tr>';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userId = localStorage.getItem('userId');
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('role');

            if (!userId || !token || role !== 'pegawai_gudang') {
                alert('Anda harus login sebagai pegawai gudang untuk melihat halaman ini.');
                window.location.href = '/login';
                return;
            }

            const tableBody = document.getElementById('tableBody2');

            // Pindahkan loadData jadi global (window)
            window.loadData = async function() {
                try {
                    const response = await fetch('/api/transaksi-pembelian/dikirim', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        alert('Gagal mengambil data transaksi: ' + (errorData.error || 'Error tidak diketahui'));
                        return;
                    }

                    const data = await response.json();
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="13" class="text-center">Tidak ada transaksi.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        const row = `<tr>
                            <td>TPB${item.ID_PEMBELIAN}</td>
                            <td>${item.pembeli.NAMA_PEMBELI}</td>
                            <td>${item.pegawai ? item.pegawai.NAMA_PEGAWAI : 'Tidak Ada'}</td>
                            <td>${item.STATUS_TRANSAKSI}</td>
                            <td>${item.TANGGAL_PESAN}</td>
                            <td>${item.TANGGAL_LUNAS}</td>
                            <td>${item.TANGGAL_KIRIM || 'Tidak Ada'}</td>
                            <td>${item.TANGGAL_AMBIL || 'Tidak Ada'}</td>
                            <td>${item.STATUS_PENGIRIMAN}</td>
                            <td>Rp ${item.TOTAL_BAYAR.toLocaleString()}</td>
                            <td>${item.POIN_DISKON}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="showDetail(${item.ID_PEMBELIAN})">Detail</button>
                                <button class="btn btn-sm btn-success ms-2" onclick="markSelesai(${item.ID_PEMBELIAN}, '${item.STATUS_PENGIRIMAN}')">Selesai</button>
                                <a href="/nota/kurir/${item.ID_PEMBELIAN}" target="_blank" class="btn btn-sm btn-secondary ms-2">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });

                } catch (error) {
                    console.error('Error:', error);
                    tableBody.innerHTML =
                        '<tr><td colspan="13" class="text-center text-danger">Gagal memuat data.</td></tr>';
                }
            };

            window.markSelesai = async function (idPembelian, statusPengiriman) {
                if (!confirm(`Yakin ingin menandai transaksi TPB${idPembelian} sebagai selesai?`)) return;

                try {
                    const payload = {
                        ID_PEMBELIAN: idPembelian,
                        STATUS_TRANSAKSI: 'Selesai'
                    };

                    if (statusPengiriman.toLowerCase() === 'delivery') {
                        payload.setTanggalSampai = true;
                    }

                    const response = await fetch('/api/transaksi-pembelian/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        const errData = await response.json();
                        alert('Gagal update status: ' + (errData.error || 'Error tidak diketahui'));
                        return;
                    }

                    alert('Status transaksi berhasil diupdate menjadi Selesai.');
                    window.loadData();

                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status.');
                }
            };

            // Load data pertama kali
            window.loadData();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const userId = localStorage.getItem('userId');
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('role');

            if (!userId || !token || role !== 'pegawai_gudang') {
                alert('Anda harus login sebagai pegawai gudang untuk melihat halaman ini.');
                window.location.href = '/login';
                return;
            }

            const tableBody = document.getElementById('tableBody3');

            try {
                const response = await fetch('/api/transaksi-pembelian/selesai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Gagal mengambil data transaksi: ' + (errorData.error || 'Error tidak diketahui'));
                    return;
                }

                const data = await response.json();
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="13" class="text-center">Tidak ada transaksi.</td></tr>';
                    return;
                }

                data.forEach(item => {
                    const row = `<tr>
                        <td>TPB${item.ID_PEMBELIAN}</td>
                        <td>${item.pembeli.NAMA_PEMBELI}</td>
                        <td>${item.pegawai ? item.pegawai.NAMA_PEGAWAI : 'Tidak Ada'}</td>
                        <td>${item.STATUS_TRANSAKSI}</td>
                        <td>${item.TANGGAL_PESAN}</td>
                        <td>${item.TANGGAL_LUNAS || 'Tidak Ada'}</td>
                        <td>${item.TANGGAL_KIRIM || 'Tidak Ada'}</td>
                        <td>${item.TANGGAL_SAMPAI || 'Tidak Ada'}</td>
                        <td>${item.TANGGAL_AMBIL || 'Tidak Ada'}</td>
                        <td>${item.STATUS_PENGIRIMAN}</td>
                        <td>Rp ${item.TOTAL_BAYAR.toLocaleString()}</td>
                        <td>${item.POIN_DISKON}</td>
                        <td>
                            <a href="/nota/kurir/${item.ID_PEMBELIAN}" target="_blank" class="btn btn-sm btn-secondary ms-2">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });

            } catch (error) {
                console.error('Error:', error);
                tableBody.innerHTML =
                    '<tr><td colspan="13" class="text-center text-danger">Gagal memuat data.</td></tr>';
            }
        });
    </script>
    <script>
        let kirimModal;

        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('kirimModal');
            kirimModal = new bootstrap.Modal(modalElement);
        });

        async function openModal(idPembelian, statusPengiriman) {
            document.getElementById('modalIdPembelian').value = idPembelian;
            document.getElementById('modalStatusPengiriman').value = statusPengiriman;
            document.getElementById('tanggalPickupGroup').style.display = statusPengiriman === 'Pickup' ? 'block' : 'none';
            document.getElementById('pegawaiSelectGroup').style.display = statusPengiriman === 'Delivery' ? 'block' : 'none';
            const pegawaiSelect = document.getElementById('pegawaiSelect');

            if (statusPengiriman === 'Pickup') {
                pegawaiSelect.removeAttribute('required');
            } else {
                pegawaiSelect.setAttribute('required', 'required');
            }

            pegawaiSelect.innerHTML = '<option value="">Memuat...</option>';

            try {
                const token = localStorage.getItem('token');
                const res = await fetch('/api/pegawai-by-role/RL004', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const pegawai = await res.json();

                pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';
                pegawai.forEach(p => {
                    pegawaiSelect.innerHTML += `<option value="${p.ID_PEGAWAI}">${p.NAMA_PEGAWAI}</option>`;
                });

                kirimModal.show();
            } catch (err) {
                alert('Gagal memuat data pegawai.');
            }
        }

        document.getElementById('kirimForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const idPembelian = document.getElementById('modalIdPembelian').value;
            const status = document.getElementById('modalStatusPengiriman').value;
            const pegawaiId = document.getElementById('pegawaiSelect').value;
            const tanggalPickup = document.getElementById('tanggalPickup').value;

            if (!pegawaiId && status === 'Delivery') {
                alert('Harap pilih pegawai.');
                return;
            }

            if (status === 'Pickup' && !tanggalPickup) {
                alert('Harap isi tanggal pengambilan.');
                return;
            }

            if (!confirm('Yakin ingin memproses pengiriman ini?')) return;

            try {
                const token = localStorage.getItem('token');
                const res = await fetch(`/api/kirim-transaksi/${idPembelian}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        ID_PEMBELIAN: idPembelian,
                        ID_PEGAWAI: status === 'Delivery' ? pegawaiId : null,
                        TANGGAL_AMBIL: status === 'Pickup' ? tanggalPickup : null
                    })
                });
                const result = await res.json();
                alert(result.message || 'Pengiriman berhasil diproses.');
                location.reload();
            } catch (err) {
                console.error(err);
                alert('Gagal memproses pengiriman.');
            }
        });
    </script>
    <script>
        async function showDetail(idPembelian) {
            const token = localStorage.getItem('token');

            const response = await fetch('/api/produk-by-pembelian', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ ID_PEMBELIAN: idPembelian })
            });

            const data = await response.json();
            const container = document.getElementById('detailBody');

            if (!data || data.length === 0) {
                container.innerHTML = "<p class='text-center'>Tidak ada produk untuk penitipan ini.</p>";
                return;
            }

            // Jika ada banyak produk, kita buatkan card per produk
            let html = '';
            data.forEach(p => {
                const prefix = p.NAMA_PRODUK ? p.NAMA_PRODUK.charAt(0).toUpperCase() : '';
                html += `
                    <div class="card shadow-sm mb-4" style="max-width: 600px; margin: auto;">
                        <div class="card-body text-center">
                            ${p.foto.length > 0 ? `
                                <div id="carousel${p.KODE_PRODUK}" class="carousel slide mb-3" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        ${p.foto.map((f, index) => `
                                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                <img src="/${f.PATH_FOTO}" class="d-block w-100 rounded" style="height: 250px; object-fit: contain;" alt="foto produk">
                                            </div>
                                        `).join('')}
                                    </div>
                                    ${p.foto.length > 1 ? `
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel${p.KODE_PRODUK}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                            <span class="visually-hidden">Sebelumnya</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel${p.KODE_PRODUK}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                            <span class="visually-hidden">Berikutnya</span>
                                        </button>
                                    ` : ''}
                                </div>
                            ` : '<p class="text-muted">Tidak ada foto untuk produk ini.</p>'}

                            <h5 class="card-title">${p.NAMA_PRODUK || p.KODE_PRODUK}</h5>
                            <p><strong>Kode Produk:</strong> ${prefix}${p.KODE_PRODUK}</p>
                            <p><strong>ID Kategori:</strong> ${p.ID_KATEGORI}</p>
                            <p><strong>Kategori:</strong> ${p.KATEGORI}</p>
                            <p><strong>Berat:</strong> ${p.BERAT} kg</p>
                            <p><strong>Garansi:</strong> ${p.GARANSI || 'Tidak Bergaransi'}</p>
                            <p><strong>Harga:</strong> Rp ${p.HARGA.toLocaleString()}</p>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
    </script>
</body>
</html>
