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

        if (role !== 'penitip') {
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
                            value="{{ $penitip->NAMA_PENITIP ?? '' }}"
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
                            value="{{ $penitip->EMAIL_PENITIP ?? '' }}"
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
                            value="{{ $penitip->NO_TELP_PENITIP ?? '' }}"
                            disabled
                        />
                    </div>

                    <div class="row-3 d-flex justify-content-between">
                        <div class="mb-3">
                            <label for="userSaldo" class="form-label fs-5 fw-bold">Saldo Anda</label>
                            <div id="userSaldo" class="points-card mx-3 mt-3">
                                <span class="saldo-value">
                                    {{ number_format($penitip->SALDO_PENITIP ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="saldo-label">Saldo</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="userPoints" class="form-label fs-5 fw-bold">Poin Anda</label>
                            <div id="userPoints" class="points-card mx-4 mt-3">
                                <span class="points-value">
                                    {{ $penitip->POIN_PENITIP ?? 0 }}
                                </span>
                                <span class="points-label">Poin</span>
                            </div>
                        </div>

                        <div class="mb-3 align-self-end">
                            <label for="userRating" class="form-label fs-5 fw-bold">Rating</label>
                            <div id="userRating" class="points-card mx-4 mt-3" style="background: linear-gradient(135deg, #ff8c00 0%, #ffa500 100%);">
                                <span class="rating-value">
                                    {{ number_format($penitip->RATING_RATA_RATA_P ?? 0, 2) }}
                                </span>
                                <span class="rating-label">Rating</span>
                            </div>
                        </div>
                    </div>
                    
                    
                </form>
                <div class="d-flex justify-content-end mt-3">
                    <a href="/penitip/histori" class="btn btn-warning text-light fw-bold">
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

</main>
@include('outer.footer')
