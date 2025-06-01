@include('outer.header')

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Profile</title>
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'pembeli') {
            alert('Akses ditolak. Halaman ini hanya untuk penitip.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
        }
    </script>
</head>

<main>
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
                            value="{{ $penitip->NAMA_PEMBELI ?? '' }}"
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
                            value="{{ $penitip->EMAIL_PEMBELI ?? '' }}"
                            disabled
                        />
                    </div>

                    <!-- No Telp -->
                    <div class="mb-3">
                        <label for="poin" class="form-label">Poin</label>
                        <input
                            type="text"
                            class="form-control"
                            id="poin"
                            name="poin"
                            value="{{ $penitip->POIN_PEMBELI ?? '' }}"
                            disabled
                        />
                    </div>
                </form>
                <div class="mt-3">
                    <a href="/pembeli/poin" class="btn btn-warning text-light fw-bold justify-content-start">
                        Tukar Poin
                    </a>
                    <a href="/history/pembelian" class="btn btn-primary text-light fw-bold justify-content-end">
                        Lihat Riwayat Pembelian
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
                        document.getElementById('fullName').value = data.NAMA_PEMBELI || '';
                        document.getElementById('email').value = data.EMAIL_PEMBELI || '';
                        document.getElementById('poin').value = data.POIN_PEMBELI || '';
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

        /* supaya rating berada di kanan bawah baris */
        .row-3 {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
    </style>

</main>
@include('outer.footer')
