<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
     <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon/logo1.webp') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        html, body {
            height: 100%;
        }
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
    <div class="header d-flex align-items-center" style="gap:12px; padding:0; height:60px;">
        <a class="navbar-brand ms-3 d-flex align-items-center" href="{{ url('/home') }}" style="height:200px;">
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
            <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const icon = document.getElementById('togglePasswordIcon');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
            </script>
            <div class="mt-3 text-center" style="color:#013c58;">
                Belum punya akun? <a href="#" style="color:#00537a;text-decoration:none;">Daftar di sini</a>
            </div>
            <div class="mt-3 text-center" style="color:#013c58;">
                <a href="#" style="color:#00537a;text-decoration:none;">Lupa Password?</a>
            </div>
        </div>
    </div>
    @include('outer.footer')
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('loginError');
        errorDiv.classList.add('d-none');
        errorDiv.innerText = '';

        try {
            const response = await fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Jika pakai Laravel Sanctum atau CSRF, tambahkan header di sini
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (data.success) {
                if (data.type === 'penitip') {
                    sessionStorage.setItem('user_type', data.type);
                    // Simpan seluruh data pembeli sebagai objek
                    sessionStorage.setItem('penitip', JSON.stringify(data.data));
                    window.location.href = '/home';
                } else if (data.type === 'pegawai') {
                    sessionStorage.setItem('user_type', data.type);
                    // Simpan seluruh data pembeli sebagai objek
                    sessionStorage.setItem('pegawai', JSON.stringify(data.data));
                    window.location.href = '/admin/dashboard';
                } else if (data.type === 'admin') {
                    sessionStorage.setItem('user_type', data.type);
                    // Simpan seluruh data pembeli sebagai objek
                    sessionStorage.setItem('pegawai', JSON.stringify(data.data));
                    window.location.href = '/admin/dashboard';
                } else if (data.type === 'pembeli') {
                    sessionStorage.setItem('user_type', data.type);
                    // Simpan seluruh data pembeli sebagai objek
                    sessionStorage.setItem('pembeli', JSON.stringify(data.data));
                    // Jika ingin akses: JSON.parse(sessionStorage.getItem('pembeli')).EMAIL_PEMBELI
                    window.location.href = '/home';
                } else if (data.type === 'organisasi') {
                    sessionStorage.setItem('user_type', data.type);
                    // Simpan seluruh data pembeli sebagai objek
                    sessionStorage.setItem('organisasi', JSON.stringify(data.data));
                    window.location.href = '/kelola_request_donasi';
                } else {
                    errorDiv.innerText = 'Tipe user tidak dikenali';
                    errorDiv.classList.remove('d-none');
                }
            } else {
                errorDiv.innerText = data.message || 'Login gagal';
                errorDiv.classList.remove('d-none');
            }
        } catch (err) {
            errorDiv.innerText = 'Terjadi kesalahan pada server';
            errorDiv.classList.remove('d-none');
        }
    });
    </script>
</body>
</html>
