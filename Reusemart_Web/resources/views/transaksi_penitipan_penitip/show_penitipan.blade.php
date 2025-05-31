<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Penitipan Berlangsung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        footer {
            margin-top: auto;
            background-color: #f8f9fa;
        }
        .modal-body {
            padding: 1rem 1.5rem;
        }

        .card img {
            max-height: 250px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 100% !important;
                margin: 0.5rem;
            }
        }
    </style>
</head>
<body>
    @include('outer.header')

    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Daftar Transaksi Penitipan Berlangsung</h3>
            <div class="input-group mt-2 mb-2 ms-2 me-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari transaksi...">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
            <a href="/profile/penitip" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i>
            </a>
        </div>

        <div id="transaksiList" class="mt-4">
            <!-- Data transaksi akan ditampilkan di sini -->
        </div>
        <!-- Modal Detail -->
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
    </main>

    <footer>
        @include('outer.footer')
    </footer>

    <script>
        async function fetchTransaksi() {
            const userId = localStorage.getItem('userId');
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('role');

            if (!userId || !token || role !== 'penitip') {
                alert('Anda harus login sebagai penitip untuk melihat halaman ini.');
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/api/transaksi-penitipan-berlangsung', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ userId })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert('Gagal mengambil data transaksi: ' + (errorData.error || 'Error tidak diketahui'));
                    return;
                }

                const data = await response.json();
                allTransaksi = data; // Simpan semua data untuk search
                renderTransaksi(data);
            } catch (error) {
                alert('Terjadi kesalahan saat mengambil data transaksi.');
                console.error(error);
            }
        }

        function renderTransaksi(transaksi) {
            const container = document.getElementById('transaksiList');

            if (transaksi.length === 0) {
                container.innerHTML = '<p>Tidak ada transaksi penitipan yang berlangsung.</p>';
                return;
            }

            let html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Penitipan</th>
                            <th>Nama Produk</th>
                            <th>Tanggal Penitipan</th>
                            <th>Tanggal Expired</th>
                            <th>Status Penitipan</th>
                            <th>Status Perpanjangan</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            transaksi.forEach(t => {
                html += `
                    <tr>
                        <td>TP${t.ID_PENITIPAN}</td>
                        <td>${t.produk.NAMA_PRODUK}</td>
                        <td>${new Date(t.TANGGAL_PENITIPAN).toLocaleDateString()}</td>
                        <td>${new Date(t.TANGGAL_EXPIRED).toLocaleDateString()}</td>
                        <td>${t.STATUS_PENITIPAN}</td>
                        <td>${t.STATUS_PERPANJANGAN ? 'Sudah' : 'Belum'}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="showDetail(${t.ID_PENITIPAN})">Detail</button>
                            ${
                                t.STATUS_PENITIPAN === 'Berlangsung' &&
                                t.STATUS_PERPANJANGAN === null &&
                                isExpiringSoon(t.TANGGAL_EXPIRED)
                                ? `<button class="btn btn-sm btn-warning ms-2" onclick="perpanjangWaktu(${t.ID_PENITIPAN})">Perpanjang</button>`
                                : ''
                            }
                            ${
                                t.STATUS_PENITIPAN === 'Berlangsung' &&
                                isExpiringSoon(t.TANGGAL_EXPIRED)
                                ? `<button class="btn btn-sm btn-danger ms-2" onclick="ambilProduk(${t.ID_PENITIPAN})">Ambil</button>`
                                : ''
                            }
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            container.innerHTML = html;
        }

        function isExpiringSoon(expiredDate) {
            const expired = new Date(expiredDate);
            const now = new Date();
            const diffTime = expired - now;
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            return diffDays <= 3;
        }


        let allTransaksi = []; // Menyimpan semua transaksi untuk pencarian

        document.getElementById('searchInput').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const filtered = allTransaksi.filter(t => {
                return (
                    (`TP${t.ID_PENITIPAN}`.toLowerCase().includes(query)) ||
                    (t.produk?.NAMA_PRODUK?.toLowerCase().includes(query)) ||
                    (new Date(t.TANGGAL_PENITIPAN).toLocaleDateString().toLowerCase().includes(query)) ||
                    (new Date(t.TANGGAL_EXPIRED).toLocaleDateString().toLowerCase().includes(query)) ||
                    (t.STATUS_PENITIPAN?.toLowerCase().includes(query))
                );
            });
            renderTransaksi(filtered);
        });


        document.addEventListener('DOMContentLoaded', fetchTransaksi);

        async function showDetail(idPenitipan) {
            const token = localStorage.getItem('token');

            const response = await fetch('/api/produk-by-penitipan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ id_penitipan: idPenitipan })
            });

            const data = await response.json();
            const container = document.getElementById('detailBody');

            if (data.length === 0) {
                container.innerHTML = "<p class='text-center'>Tidak ada produk untuk penitipan ini.</p>";
            } else {
                const p = data[0]; // Asumsikan satu produk per penitipan, jika banyak bisa looping
                const prefix = p.NAMA_PRODUK ? p.NAMA_PRODUK.charAt(0).toUpperCase() : '';
                let html = `
                    <div class="d-flex justify-content-center">
                        <div class="card shadow-sm" style="width: 100%; max-width: 600px;">
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
                    </div>
                `;
                container.innerHTML = html;
            }

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
        async function perpanjangWaktu(idPenitipan) {
            const token = localStorage.getItem('token');
            if (!confirm("Apakah Anda yakin ingin memperpanjang waktu penitipan selama 30 hari?")) return;

            try {
                const response = await fetch('/api/perpanjang-penitipan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ id_penitipan: idPenitipan })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Waktu penitipan berhasil diperpanjang.');
                    fetchTransaksi(); // Refresh data
                } else {
                    alert('Gagal memperpanjang: ' + (data.error || 'Terjadi kesalahan.'));
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat memperpanjang waktu.');
            }
        }
        async function ambilProduk(idPenitipan) {
            const token = localStorage.getItem('token');
            if (!confirm("Apakah Anda yakin ingin mengonfirmasi bahwa produk ini akan diambil?")) return;

            try {
                const response = await fetch('/api/ambil-penitipan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ id_penitipan: idPenitipan })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Produk berhasil ditandai sebagai "Akan Diambil".');
                    fetchTransaksi(); // Refresh data
                } else {
                    alert('Gagal mengubah status: ' + (data.error || 'Terjadi kesalahan.'));
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengubah status.');
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
