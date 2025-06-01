<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
    <title>Profile</title>
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'penitip') {
            alert('Akses ditolak. Halaman ini hanya untuk penitip.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
        }
    </script>
</head>
<body>
    @include('outer.header')
    <div class="container pt-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <form id="profile-form" action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Nama</label>
                        <input
                            type="text"
                            class="form-control"
                            id="fullName"
                            name="fullName"
                            value=""
                            disabled
                        />
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value=""
                            disabled
                        />
                    </div>

                    <!-- No Telp -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. Telp</label>
                        <input
                            type="text"
                            class="form-control"
                            id="phone"
                            name="phone"
                            value=""
                            disabled
                        />
                    </div>

                    <div class="row-3 d-flex justify-content-between">
                        <div class="mb-3">
                            <label for="userSaldo" class="form-label fs-5 fw-bold">Saldo Anda</label>
                            <div id="userSaldo" class="points-card mx-3 mt-3">
                                <span class="saldo-value">

                                </span>
                                <span class="saldo-label">Saldo</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="userPoints" class="form-label fs-5 fw-bold">Poin Anda</label>
                            <div id="userPoints" class="points-card mx-4 mt-3">
                                <span class="points-value">

                                </span>
                                <span class="points-label">Poin</span>
                            </div>
                        </div>

                        <div class="mb-3 align-self-end">
                            <label for="userRating" class="form-label fs-5 fw-bold">Rating</label>
                            <div id="userRating" class="points-card mx-4 mt-3" style="background: linear-gradient(135deg, #ff8c00 0%, #ffa500 100%);">
                                <span class="rating-value">

                                </span>
                                <span class="rating-label">Rating</span>
                            </div>
                        </div>
                    </div>


                </form>
                <div class="d-flex justify-content-end mt-3 gap-2">
                    <a href="/penitip/penitipan" class="btn btn-primary fw-bold">
                        Lihat Daftar Titipan
                    </a>
                    <a href="/penitip/histori" class="btn btn-warning text-light fw-bold">
                        Lihat Riwayat Penjualan
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchUserData();
        });

        async function fetchUserData() {
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('role');
            const userId = localStorage.getItem('userId');

            if (!role || !userId) {
                console.warn('User belum login.');
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
                    return;
                }

                const data = await response.json();

                let userName = '';
                let profileLink = '';

                switch (role) {
                    case 'pegawai':
                        userName = data.NAMA_PEGAWAI;
                        break;
                    case 'penitip':
                        document.getElementById('fullName').value = data.NAMA_PENITIP || '';
                        document.getElementById('email').value = data.EMAIL_PENITIP || '';
                        document.getElementById('phone').value = data.NO_TELP_PENITIP || '';
                        document.querySelector('#userSaldo .saldo-value').textContent = new Intl.NumberFormat('id-ID').format(data.SALDO_PENITIP || 0);
                        document.querySelector('#userPoints .points-value').textContent = data.POIN_PENITIP || 0;
                        document.querySelector('#userRating .rating-value').textContent = (data.RATING_RATA_RATA_P || 0).toFixed(2);
                        break;
                    case 'pembeli':
                        userName = data.NAMA_PEMBELI;
                        profileLink = '/profile/pembeli';
                        break;
                    case 'organisasi':
                        userName = data.NAMA_ORGANISASI;
                        profileLink = '/profile/organisasi';
                        break;
                    default:
                        console.error('Role tidak dikenali:', role);
                        return;
                }

                // Update UI
                document.getElementById('userNameNav').style.display = 'block';
                document.getElementById('loginBtnNav').style.display = 'none';
                document.getElementById('registerBtnNav').style.display = 'none';

                const userLink = document.getElementById('userNameText');
                userLink.textContent = userName;
                userLink.href = profileLink;

            } catch (err) {
                console.error('Error saat fetch user:', err);
            }
        }
    </script>
    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .points-card {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #00537a 0%, #ffba42 100%);
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(37, 117, 252, 0.4);
            user-select: none;
            width: max-content;
        }

        .saldo-value, .points-value, .rating-value {
            font-size: 2rem;
        }

        .saldo-label, .points-label, .rating-label {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* warna khusus untuk rating */
        #userRating.points-card {
            background: linear-gradient(135deg, #ff8c00 0%, #ffa500 100%);
            box-shadow: 0 4px 15px rgba(255, 140, 0, 0.5);
        }

        /* supaya rating berada di kanan bawah baris */
        .row-3 {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
    </style>

</body>
@include('outer.footer')
</html>

