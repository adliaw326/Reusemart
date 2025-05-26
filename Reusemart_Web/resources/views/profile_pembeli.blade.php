<!-- filepath: resources/views/profile_pembeli.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .profile-card, .order-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 24px;
            margin-top: 24px;
            margin-bottom: 32px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .profile-info-title {
            color: #00537a;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .profile-actions .btn {
            margin: 0 8px 8px 0;
        }
        .order-status {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .order-status-item {
            flex: 1;
            text-align: center;
        }
        .order-status-item i {
            font-size: 1.7rem;
            color: #ffba42;
            margin-bottom: 4px;
        }
        .order-status-item span {
            display: block;
            font-size: 0.95rem;
            color: #00537a;
        }
        @media (max-width: 575.98px) {
            .profile-card, .order-section {
                padding: 12px;
            }
            .profile-info-title {
                font-size: 1.1rem;
            }
            .order-status-item i {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body style="background: #f6f8fa;">
    <!-- Header tanpa search bar -->
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

    <div class="container mt-4">
        <h3 id="profileName" class="text-center mb-3">Pembeli</h3>
        <!-- filepath: resources/views/profile_pembeli.blade.php -->
        <div class="profile-card">
            <div class="profile-info-title text-center">Informasi Akun</div>
            <div class="mb-2"><b>Nama:</b> <span id="profileName2">Pembeli</span></div>
            <div class="mb-2"><b>Email:</b> <span id="profileEmail2">-</span></div>
            <div class="mb-2"><b>Poin:</b> <span id="profilePoin">0</span></div>
            <div class="mb-2"><b>Alamat:</b> <span id="profileAddress">Belum diatur</span></div>
            <div class="profile-actions mt-3 text-start">
                <button id="alamatBtn" class="btn btn-outline-warning btn-sm"></button>
            </div>
        </div>
        <div class="order-section">
            <div class="profile-info-title text-center">Pesanan Saya</div>
            <div class="order-status row text-center mb-3">
                <div class="order-status-item col-3">
                    <i class="fa fa-credit-card"></i>
                    <span>Belum Bayar</span>
                </div>
                <div class="order-status-item col-3">
                    <i class="fa fa-box"></i>
                    <span>Dikemas</span>
                </div>
                <div class="order-status-item col-3">
                    <i class="fa fa-truck"></i>
                    <span>Dikirim</span>
                </div>
                <div class="order-status-item col-3">
                    <i class="fa fa-check-circle"></i>
                    <span>Selesai</span>
                </div>
            </div>
            <a href="{{ url('/history_pembelian') }}" class="btn btn-warning btn-sm w-100">Lihat Semua Pesanan</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const pembeli = JSON.parse(sessionStorage.getItem('pembeli'));
            if (!pembeli || !pembeli.ID_PEMBELI) {
                window.location.href = '/login';
                return;
            }
            document.getElementById('profileName').innerText = pembeli.NAMA_PEMBELI || 'Pembeli';
            document.getElementById('profileName2').innerText = pembeli.NAMA_PEMBELI || 'Pembeli';
            document.getElementById('profileEmail2').innerText = pembeli.EMAIL_PEMBELI || '-';
            document.getElementById('profilePoin').innerText = pembeli.POIN_PEMBELI || '-';

            // Ambil lokasi alamat default dari API (langsung string)
            let lokasi = '';
            try {
                const res = await fetch(`http://localhost:8000/api/alamat/pembeli/${pembeli.ID_PEMBELI}/default`);
                lokasi = await res.json();
            } catch (e) {}

            const alamatSpan = document.getElementById('profileAddress');
            alamatSpan.innerText = lokasi ? lokasi : 'Belum diatur';

            // Button alamat
            const alamatBtn = document.getElementById('alamatBtn');
            if (!lokasi) {
                alamatBtn.textContent = 'Tambah Alamat';
                alamatBtn.classList.remove('btn-outline-warning');
                alamatBtn.classList.add('btn-warning');
            } else {
                alamatBtn.textContent = 'Ubah Alamat';
                alamatBtn.classList.remove('btn-warning');
                alamatBtn.classList.add('btn-outline-warning');
            }
            alamatBtn.onclick = function() {
                window.location.href = '/alamat';
            };
        });
    </script>
    <body class="d-flex flex-column min-vh-100" style="background: #f6f8fa;">
        <!-- ...navbar dan konten lain... -->
        <div class="container mt-4 flex-grow-1">
            <!-- ...semua konten utama di sini... -->
        </div>
        @include('outer.footer')
    </body>
</body>
</html>
