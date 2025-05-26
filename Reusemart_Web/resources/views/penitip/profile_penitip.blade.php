@include('outer.header')

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Profile</title>
</head>

<main>
    <div class="container pt-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <form id="profile-form" action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Nama</label>
                        <input
                            type="text"
                            class="form-control"
                            id="fullName"
                            name="fullName"
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
                            disabled
                        />
                    </div>

                    <div class="row-3">
                        <div class="mb-3">
                            <label for="userSaldo" class="form-label fs-5 fw-bold">Saldo Anda</label>
                            <div id="userSaldo" class="points-card mx-3 mt-3">
                                <span class="saldo-value">Rp0</span>
                                <span class="saldo-label">Saldo</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="userPoints" class="form-label fs-5 fw-bold">Poin Anda</label>
                            <div id="userPoints" class="points-card mx-4 mt-3">
                                <span class="points-value">0</span>
                                <span class="points-label">Poin</span>
                            </div>
                        </div>
                    </div>
                    
                    
                </form>
                <div class="d-flex justify-content-end mt-3">
                    <a href="/history-penitip" class="btn btn-warning text-light fw-bold">
                        Lihat Riwayat Penjualan
                    </a>
                </div>
            </div>
        </div>
    </div>

    
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

            .points-value {
            font-size: 2rem;
            }

            .points-label {
            font-size: 1rem;
            opacity: 0.8;
            }
            .saldo-card {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
                color: white;
                font-weight: 700;
                font-size: 1.5rem;
                padding: 14px 24px;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 176, 155, 0.3);
                user-select: none;
                width: max-content;
                transition: all 0.3s ease-in-out;
            }

            .saldo-value {
                font-size: 2.2rem;
            }

            .saldo-label {
                font-size: 1rem;
                opacity: 0.85;
            }


        /* .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .change-photo-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: transparent;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hidden-input {
            display: none;
        }

        .camera-icon {
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 30px;
        }

        .profile-container:hover .camera-icon {
            opacity: 1;
        }

        .profile-container:hover .change-photo-btn {
            background-color: rgba(255, 255, 255, 0.5);
        }

        #edit-profile.btn-link {
            color: #0d6efd;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        } */
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            async function loadPenitipProfile() {
                try {
                    const token = localStorage.getItem('token');
                    const response = await fetch('http://localhost:8000/api/penitip/profile', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Gagal mengambil data profil');

                    const result = await response.json();
                    let data = result.data;

                    // Jika data tidak ditemukan, gunakan ID_PENITIP default dan fetch data dari backend
                    if (!data || Object.keys(data).length === 0) {
                        console.warn('Data profil kosong, mencoba ambil data dengan ID_PENITIP default');

                        const defaultId = 'T01';
                        const fallbackResponse = await fetch(`http://localhost:8000/api/penitip/cari/${defaultId}`, {
                            method: 'PUT',
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (!fallbackResponse.ok) throw new Error('Gagal mengambil data dari fallback ID');

                        const fallbackResult = await fallbackResponse.json();
                        data = fallbackResult.data;

                        if (!data) throw new Error('Data kosong setelah fallback');
                    }

                    // Tampilkan ke form
                    document.getElementById('fullName').value = data.NAMA_PENITIP || '';
                    document.getElementById('email').value = data.EMAIL_PENITIP || '';

                    // Tampilkan saldo (format rupiah)
                    document.querySelector('.saldo-value').textContent = formatRupiah(data.saldo || 0);
                    document.querySelector('.points-value').textContent = data.saldo || 0;

                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                }
            }

            // Format angka ke Rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            loadPenitipProfile();
        });

        
    </script>
</main>
@include('outer.footer')
