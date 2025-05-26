<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Kelola Request Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { background: #0b1e33; color: #fff; min-height: 100vh; display: flex; flex-direction: column; }
        .card-white { background: #fff; color: #000; }
        #orgName {
            margin: 2rem 0 1.5rem 0;
            font-weight: 700;
            font-size: 1.8rem;
            text-align: center;
            color: #ffba42;
        }
        #requestContainer {
            flex-grow: 1;
        }
    </style>
</head>
<body>

    @include('outer.header')

    <div class="container">
        <div id="orgName">-- Nama Organisasi --</div>

        <!-- Tombol Tambah Request -->
        <div class="text-end mb-3">
            <button class="btn btn-success" onclick="window.location.href='/create_request_donasi'">
                <i class="fas fa-plus-circle"></i> Tambah Request Donasi
            </button>
        </div>

        <div id="requestContainer" class="row justify-content-center"></div>
    </div>

    @include('outer.footer')

    <script>
    document.addEventListener('DOMContentLoaded', async function () {
        const user = JSON.parse(sessionStorage.getItem('organisasi'));
        const container = document.getElementById('requestContainer');
        const orgNameElem = document.getElementById('orgName');

        if (!user || !user.ID_ORGANISASI) {
            alert('Organisasi tidak terdeteksi. Silakan login ulang.');
            window.location.href = '/login';
            return;
        }

        orgNameElem.textContent = user.NAMA_ORGANISASI;

        try {
            const res = await fetch(`http://localhost:8000/api/request_donasi/organisasi/${user.ID_ORGANISASI}`);
            const data = await res.json();

            if (!Array.isArray(data) || data.length === 0) {
                container.innerHTML = `<div class="alert alert-warning">Belum ada request donasi untuk organisasi ini.</div>`;
                return;
            }

            data.forEach(req => {
                const status = req.STATUS_REQUEST || 'unknown';

                const produk = req.produk || {};
                const namaProduk = produk.NAMA_PRODUK || '-';
                const kategori = produk.KATEGORI || '-';
                const garansi = produk.GARANSI || '-';

                const card = document.createElement('div');
                card.className = 'col-md-8 mb-3';

                card.innerHTML = `
                    <div class="card card-white shadow-sm">
                        <div class="card-body">
                            <p><strong>ID Request:</strong> ${req.ID_REQUEST}</p>
                            <p><strong>Detail Request:</strong> ${req.DETAIL_REQUEST}</p>
                            <p><strong>Status:</strong> <span class="badge ${getBadgeClass(status)}">${status}</span></p>
                            <hr>
                            <p><strong>Nama Produk:</strong> ${namaProduk}</p>
                            <p><strong>Kategori:</strong> ${kategori}</p>
                            <p><strong>Garansi:</strong> ${garansi}</p>
                            ${status.toLowerCase() === 'pending' ? `
                                <div class="text-end mt-3">
                                    <button class="btn btn-sm btn-danger" onclick="batalkanRequest('${req.ID_REQUEST}')">
                                        <i class="fas fa-times-circle"></i> Batalkan
                                    </button>
                                </div>` : ''}
                        </div>
                    </div>
                `;

                container.appendChild(card);
            });

        } catch (err) {
            console.error('Fetch error:', err);
            container.innerHTML = `<div class="alert alert-danger">Gagal memuat data request donasi.</div>`;
        }
    });

    function getBadgeClass(status) {
        switch (status.toLowerCase()) {
            case 'disetujui': return 'bg-success';
            case 'ditolak': return 'bg-danger';
            case 'pending': return 'bg-warning text-dark';
            default: return 'bg-secondary';
        }
    }

    async function batalkanRequest(idRequest) {
        if (!confirm('Yakin ingin membatalkan request ini?')) return;

        try {
            const res = await fetch(`http://localhost:8000/api/request_donasi/${idRequest}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const result = await res.json();

            if (res.ok) {
                alert('Request berhasil dibatalkan.');
                location.reload();
            } else {
                alert(result.message || 'Gagal membatalkan request.');
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan saat menghapus.');
        }
    }
    </script>

</body>
</html>
