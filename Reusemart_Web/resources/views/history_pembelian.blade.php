<!-- filepath: resources/views/history_pembelian.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .status-badge {
            font-size: 0.95rem;
            padding: 4px 12px;
            border-radius: 12px;
        }
        .status-menunggu { background: #ffe082; color: #795548; }
        .status-lunas { background: #a5d6a7; color: #1b5e20; }
        .status-dikirim { background: #81d4fa; color: #01579b; }
        .status-selesai { background: #ffd54f; color: #ff6f00; }
        .status-batal { background: #ef9a9a; color: #b71c1c; }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <!-- Header sama seperti profile_pembeli -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-no-search" style="background-color: #013c58; height: 60px;">
        <div class="container-fluid align-items-center h-100">
            <a class="navbar-brand ms-2 me-3 d-flex align-items-center" href="{{ url('/home') }}">
                <img src="{{ asset('icon/logo.webp') }}" alt="Barang Bekas Murah Logo" style="max-height: 200px">
            </a>
            <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarNav">
                <ul class="navbar-nav ms-auto text-warning d-flex align-items-center">
                    <li class="nav-item">
                        <a href="#" class="text-warning mx-2">
                            <img src="{{ asset('icon/shopping-cart.png') }}" alt="Shopping Cart Icon" width="20px">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="text-warning mx-2">
                            <img src="{{ asset('icon/message.png') }}" alt="Message Icon" width="20px">
                        </a>
                    </li>
                    <li class="nav-item mx-2 d-none d-lg-block" style="border-left: 2px solid #ffba42; height: 25px;"></li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-warning" href="{{ url('/home') }}">Beranda</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-warning" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item mx-1" id="loginBtnNav">
                        <a href="{{ url('/login') }}" class="btn btn-outline-warning text-warning" style="transition: none;">Masuk</a>
                    </li>
                    <li class="nav-item mx-1" id="registerBtnNav">
                        <a href="{{ url('/register') }}" class="btn btn-warning text-dark" style="transition: none;">Daftar</a>
                    </li>
                    <li class="nav-item mx-1" id="userNameNav" style="display:none;">
                        <a href="#" class="nav-link text-warning fw-bold" id="userNameText"></a>
                    </li>
                </ul>
                <script>
                    // Ambil tipe user dari sessionStorage
                    const userType = sessionStorage.getItem('user_type');
                    let userName = null;
                    let profileUrl = '#';

                    if (userType === 'pembeli') {
                        const pembeli = JSON.parse(sessionStorage.getItem('pembeli') || '{}');
                        userName = pembeli.NAMA_PEMBELI;
                        profileUrl = '/profile_pembeli';
                    } else if (userType === 'penitip') {
                        const penitip = JSON.parse(sessionStorage.getItem('penitip') || '{}');
                        userName = penitip.NAMA_PENITIP;
                        profileUrl = '/profile_penitip';
                    } else if (userType === 'pegawai') {
                        const pegawai = JSON.parse(sessionStorage.getItem('pegawai') || '{}');
                        userName = pegawai.NAMA_PEGAWAI;
                        profileUrl = '/profile_pegawai';
                    } else if (userType === 'organisasi') {
                        const organisasi = JSON.parse(sessionStorage.getItem('organisasi') || '{}');
                        userName = organisasi.NAMA_ORGANISASI;
                        profileUrl = '/profile_organisasi';
                    } else if (userType === 'admin') {
                        const pegawai = JSON.parse(sessionStorage.getItem('pegawai') || '{}');
                        userName = pegawai.NAMA_PEGAWAI || 'Admin';
                        profileUrl = '/profile_pegawai';
                    }

                    if (userName) {
                        const userNameText = document.getElementById('userNameText');
                        userNameText.innerText = userName;
                        userNameText.href = profileUrl;
                        document.getElementById('userNameNav').style.display = 'block';
                        document.getElementById('loginBtnNav').style.display = 'none';
                        document.getElementById('registerBtnNav').style.display = 'none';
                    }
                </script>
            </div>
        </div>
    </nav>

    <div class="container py-4 flex-grow-1">
        <div class="row align-items-center mb-4">
            <div class="col-auto">
                <a href="{{ url('/profile_pembeli') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
            <div class="col ps-0">
                <h3 class="mb-0 text-center text-md-start">Riwayat Pembelian</h3>
            </div>
        </div>
        <div id="historyList"></div>
        <div id="emptyMsg" class="text-center text-muted mt-5" style="display:none;">Belum ada transaksi pembelian.</div>
    </div>
    @include('outer.footer')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const pembeli = JSON.parse(sessionStorage.getItem('pembeli'));
            if (!pembeli || !pembeli.ID_PEMBELI) {
                window.location.href = '/login';
                return;
            }
            const historyList = document.getElementById('historyList');
            const emptyMsg = document.getElementById('emptyMsg');
            try {
                const res = await fetch(`http://localhost:8000/api/transaksi_pembelian/history/${pembeli.ID_PEMBELI}`);
                const data = await res.json();
                if (!data.length) {
                    emptyMsg.style.display = 'block';
                    return;
                }
                let html = `<div class="table-responsive"><table class="table table-bordered align-middle bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pembelian</th>
                            <th>Status</th>
                            <th>Tanggal Pesan</th>
                            <th>Tanggal Lunas</th>
                            <th>Tanggal Kirim</th>
                            <th>Tanggal Sampai</th>
                        </tr>
                    </thead>
                    <tbody>`;
                data.forEach(item => {
                    let statusClass = '';
                    switch ((item.STATUS_TRANSAKSI || '').toLowerCase()) {
                        case 'menunggu pembayaran': statusClass = 'status-menunggu'; break;
                        case 'lunas': statusClass = 'status-lunas'; break;
                        case 'dikirim': statusClass = 'status-dikirim'; break;
                        case 'selesai': statusClass = 'status-selesai'; break;
                        case 'batal': statusClass = 'status-batal'; break;
                        default: statusClass = 'bg-secondary text-white';
                    }
                    html += `<tr>
                        <td>${item.ID_PEMBELIAN}</td>
                        <td><span class="status-badge ${statusClass}">${item.STATUS_TRANSAKSI}</span></td>
                        <td>${item.TANGGAL_PESAN || '-'}</td>
                        <td>${item.TANGGAL_LUNAS || '-'}</td>
                        <td>${item.TANGGAL_KIRIM || '-'}</td>
                        <td>${item.TANGGAL_SAMPAI || '-'}</td>
                    </tr>`;
                });
                html += `</tbody></table></div>`;
                historyList.innerHTML = html;
            } catch (e) {
                historyList.innerHTML = `<div class="alert alert-danger">Gagal memuat data pembelian.</div>`;
            }
        });
    </script>
</body>
</html>
