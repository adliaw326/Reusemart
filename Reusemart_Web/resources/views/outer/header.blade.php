<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #013c58; height: 60px;">
    <div class="container-fluid align-items-center h-100">
        <!-- Logo -->
        <a class="navbar-brand ms-2 me-3 d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('icon/logo.webp') }}" alt="Barang Bekas Murah Logo" style="max-height: 200px;">
        </a>

        <!-- Search Bar -->
        <form class="d-none d-lg-flex flex-grow-1 mx-2 position-relative" style="max-width: 500px;">
            <input class="form-control pe-5" type="search" placeholder="Cari produk..." aria-label="Search">
            <i class="fa fa-search position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%); color: #013c58;"></i>
        </form>

        <!-- Navbar Toggler for Mobile -->
        <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarNav">
            <ul class="navbar-nav ms-auto text-warning d-flex align-items-center">
                <!-- Search Bar for Mobile -->
                <li class="nav-item w-100 d-lg-none mb-2">
                    <form class="d-flex position-relative w-100">
                        <input class="form-control pe-5" type="search" placeholder="Cari produk..." aria-label="Search">
                        <i class="fa fa-search position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%); color: #013c58;"></i>
                    </form>
                </li>
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
                    <a class="nav-link text-warning" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-warning" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                </li>
                <li class="nav-item mx-2" id="cartNav" style="display: none;">
                    <a class="nav-link text-warning" href="{{ url('/keranjang') }}">Keranjang</a>
                </li>
                <li class="nav-item mx-1" id="loginBtnNav">
                    <a href="{{ url('/login') }}" class="btn btn-outline-warning text-warning" style="transition: none;">Masuk</a>
                </li>
                <li class="nav-item mx-1" id="registerBtnNav">
                    <a href="{{ url('/registrasi') }}" class="btn btn-warning text-dark" style="transition: none;">Daftar</a>
                </li>

                <!-- User info and Logout (hidden by default) -->
                <li class="nav-item mx-1" id="userNameNav" style="display:none; display: flex; align-items: center; gap: 8px;">
                    <a href="#" class="nav-link text-warning fw-bold" id="userNameText"></a>
                    <button id="logoutBtn" class="btn btn-outline-warning btn-sm" style="height: 30px; line-height: 1; padding: 0 10px;">Logout</button>
                </li>
            </ul>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    fetchUserData();
                });

                const logoutBtn = document.getElementById('logoutBtn');

                if (logoutBtn) {
                    logoutBtn.addEventListener('click', function () {
                        localStorage.removeItem('token');
                        localStorage.removeItem('role');
                        localStorage.removeItem('userId');

                        document.getElementById('userNameNav').style.display = 'none';
                        document.getElementById('loginBtnNav').style.display = 'inline-block';
                        document.getElementById('registerBtnNav').style.display = 'inline-block';

                        window.location.href = '/login';
                    });
                }

                async function fetchUserData() {
                    const token = localStorage.getItem('token');
                    const role = localStorage.getItem('role');
                    const userId = localStorage.getItem('userId');

                    // Jika belum login, tampilkan login & register, sembunyikan logout
                    if (!token || !role || !userId) {
                        document.getElementById('userNameNav').style.display = 'none';
                        document.getElementById('loginBtnNav').style.display = 'inline-block';
                        document.getElementById('registerBtnNav').style.display = 'inline-block';

                        document.getElementById('cartNav').style.display = 'none';
                        return;
                    }

                    try {
                        const response = await fetch('/api/get-user-data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify({ role, userId })
                        });

                        if (!response.ok) {
                            const error = await response.json();
                            console.error('Gagal mengambil data user:', error);
                            document.getElementById('userNameNav').style.display = 'none';
                            document.getElementById('loginBtnNav').style.display = 'inline-block';
                            document.getElementById('registerBtnNav').style.display = 'inline-block';
                            return;
                        }

                        const data = await response.json();

                        let userName = '';
                        let profileLink = '';

                        switch (role) {
                            case 'pegawai':
                                userName = data.NAMA_PEGAWAI;
                                profileLink = '/profile/pegawai';
                                break;
                            case 'pegawai_gudang':
                                userName = data.NAMA_PEGAWAI;
                                profileLink = '/pegawai_gudang/pilih_transaksi';
                                break;
                            case 'penitip':
                                userName = data.NAMA_PENITIP;
                                profileLink = '/profile/penitip';
                                break;
                            case 'pembeli':
                                userName = data.NAMA_PEMBELI;
                                profileLink = '/profile/pembeli';
                                break;
                            case 'organisasi':
                                userName = data.NAMA_ORGANISASI;
                                profileLink = '/profile/organisasi';
                                break;
                            case 'owner':
                                userName = data.NAMA_PEGAWAI;
                                profileLink = '/owner/dashboard';
                                break;
                            default:
                                console.error('Role tidak dikenali:', role);
                                return;
                        }

                        // Update UI kalau user valid
                        document.getElementById('userNameNav').style.display = 'flex';
                        document.getElementById('loginBtnNav').style.display = 'none';
                        document.getElementById('registerBtnNav').style.display = 'none';

                        document.getElementById('cartNav').style.display = 'inline-block';


                        const userLink = document.getElementById('userNameText');
                        userLink.textContent = userName;
                        userLink.href = profileLink;

                    } catch (err) {
                        console.error('Error saat fetch user:', err);
                        document.getElementById('userNameNav').style.display = 'none';
                        document.getElementById('loginBtnNav').style.display = 'inline-block';
                        document.getElementById('registerBtnNav').style.display = 'inline-block';
                    }
                }
            </script>
        </div>
    </div>
</nav>

<style>
/* Responsive logo size */
@media (max-width: 991.98px) {
    .navbar-brand img {
        max-height: 32px;
    }
}
@media (max-width: 575.98px) {
    .navbar-brand img {
        max-height: 28px;
    }
    .navbar .form-control {
        font-size: 0.95rem;
        padding-right: 2.2rem;
    }
    .navbar .fa-search {
        right: 12px !important;
        font-size: 1rem;
    }
}
</style>
