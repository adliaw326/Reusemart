<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reusemart Login</title>
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        html, body { height: 100%; }
        body {
            min-height: 100vh;
            background: #fff;
            display: flex;
            flex-direction: column;
        }
        .header {
            width: 100%;
            background-color: #013c58;
            color: #fff;
            padding: 18px 0 18px 30px;
            font-size: 22px;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .main-content {
            flex: 1 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            width: 100%;
        }
        .login-container {
            margin-top: 120px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
            max-width: 400px;
            width: 100%;
        }
        .form-control {
            background-color: #fffaec;
            border: 1px solid #f5a201;
            color: #0b1e33;
        }
        .form-control:focus {
            background-color: #fffbe7;
            border-color: #ffba42;
            box-shadow: 0 0 0 0.2rem rgba(245,162,1,.15);
            color: #0b1e33;
        }
        @media (max-width: 576px) {
            .header {
                font-size: 18px;
                padding: 14px 0 14px 16px;
            }
            .login-container {
                margin-top: 90px;
                padding: 20px 10px 16px 10px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100; margin-top: 1rem; margin-right: 1rem;">
        @if(session('status'))
        <div id="statusToast" class="toast align-items-center text-bg-info border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('status') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
    <div class="header d-flex align-items-center" style="gap:12px; padding:0; height:60px;">
        <a class="navbar-brand ms-3 d-flex align-items-center" href="{{ url('/show') }}" style="height:200px;">
            <img src="{{ asset('icon/logo.webp') }}" alt="Barang Bekas Murah Logo" style="max-height:200px; height:200px;">
        </a>
    </div>
    <div class="main-content">
        <div class="login-container">
            <h2 class="mb-4 text-center" style="color:#0b1e33;">Login</h2>
            <form id="loginForm">
                <div class="mb-3 text-start">
                    <label for="email" class="form-label" style="color:#013c58;">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-3 text-start position-relative">
                    <label for="password" class="form-label" style="color:#013c58;">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
                    <span class="position-absolute end-0" style="top:73%; transform:translateY(-50%); cursor:pointer; margin-right:16px;" onclick="togglePassword()">
                        <i id="togglePasswordIcon" class="fa fa-eye"></i>
                    </span>
                </div>
                <button type="submit" class="btn w-100" style="background:#013c58; color:#fff;">Login</button>
                <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
            </form>
            <div class="mt-3 text-center" style="color:#013c58;">
                Belum punya akun? <a href="/registrasi" style="color:#00537a;text-decoration:none;">Daftar di sini</a>
            </div>
            <div class="mt-3 text-center" style="color:#013c58;">
                <a href="/forgot-password" style="color:#00537a;text-decoration:none;">Lupa Password?</a>
            </div>
        </div>
    </div>
    @include('outer.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const loginError = document.getElementById('loginError');

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const result = await response.json();

            if (!response.ok) {
                loginError.classList.remove('d-none');
                loginError.textContent = result.message || 'Login gagal!';
                return;
            }

            // Simpan token di localStorage
            localStorage.setItem('token', result.token);
            localStorage.setItem('role', result.role);
            localStorage.setItem('userId', result.userId);

            // Arahkan ke dashboard sesuai role
            switch (result.role) {
                case 'admin':
                    window.location.href = '/admin/dashboard';
                    break;
                case 'cs':
                    window.location.href = '/kelola_penitip';
                    break;
                case 'pegawai_gudang':
                    window.location.href = '/pegawai_gudang/show_transaksi_penitipan';
                    break;
                case 'organisasi':
                    window.location.href = '/dashboard/organisasi';
                    break;
                case 'penitip':
                    window.location.href = '/show';
                    break;
                case 'pembeli':
                    window.location.href = '/show';
                    break;
                default:
                    window.location.href = '/show';
            }

        } catch (err) {
            loginError.classList.remove('d-none');
            loginError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    });
    </script>
</body>
</html>
