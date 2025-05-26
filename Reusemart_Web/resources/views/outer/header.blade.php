<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #013c58; height: 60px;">
    <div class="container-fluid align-items-center h-100">
        <!-- Logo -->
        <a class="navbar-brand ms-2 me-3 d-flex align-items-center" href="{{ url('/home') }}">
            <img src="{{ asset('icon/logo.webp') }}" alt="Barang Bekas Murah Logo" style="max-height: 200px;">
        </a>

        <!-- Search Bar -->
        <form class="d-none d-lg-flex flex-grow-1 mx-2 position-relative" style="max-width: 500px;">
            <input class="form-control pe-5 mt-3" type="search" placeholder="Cari produk..." aria-label="Search">
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
                <script>
                    // Ambil nama dan tipe user dari sessionStorage
                    const userName = sessionStorage.getItem('user_name');
                    const userType = sessionStorage.getItem('user_type'); // Pastikan saat login, user_type juga disimpan

                    if(userName) {
                        const userNameText = document.getElementById('userNameText');
                        let profileUrl = '#';
                        if (userType === 'pembeli') profileUrl = '/profil_pembeli';
                        else if (userType === 'penitip') profileUrl = '/profil_penitip';
                        else if (userType === 'pegawai') profileUrl = '/profil_pegawai';
                        else profileUrl = '#';

                        userNameText.innerText = userName;
                        userNameText.href = profileUrl;
                        document.getElementById('userNameNav').style.display = 'block';
                        document.getElementById('loginBtnNav').style.display = 'none';
                        document.getElementById('registerBtnNav').style.display = 'none';
                    }
                </script>
            </ul>
            <script>
                // Ambil tipe user dari sessionStorage
                const userType = sessionStorage.getItem('user_type');
                console.log(sessionStorage.getItem('pembeli'));
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
                    profileUrl = '/profile_pegawai'; // atau buat /profile_admin jika ada
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
