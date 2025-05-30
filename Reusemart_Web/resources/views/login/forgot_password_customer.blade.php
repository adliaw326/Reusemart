<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        body {
            min-height: 100vh;
            background: #fff;
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
        .footer {
            width: 100%;
            background-color: #ffba42;
            color: #013c58;
            text-align: center;
            padding: 18px 10px 10px 10px;
            position: fixed;
            bottom: 0;
            left: 0;
        }
        .footer .sosmed a {
            color: #013c58;
            margin: 0 10px;
            font-size: 22px;
        }
        .footer .phone {
            display: block;
            margin: 8px 0 4px 0;
            font-size: 15px;
        }
        .footer .copyright {
            font-size: 13px;
            color: #013c58;
        }
    </style>
</head>
<body>
    <div class="header">Reusemart</div>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="login-container">
            <h2 class="mb-4 text-center" style="color:#0b1e33;">Ubah Password</h2>

            {{-- Alert status atau error dari session --}}
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Validasi error --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reset.password.update') }}">
                @csrf

                {{-- hidden input untuk email --}}
                <input type="hidden" name="email" value="{{ request()->query('email') }}">

                <div class="mb-3 text-start">
                    <label for="password" class="form-label" style="color:#013c58;">Password Baru</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password baru" required>
                </div>

                <div class="mb-3 text-start">
                    <label for="password_confirmation" class="form-label" style="color:#013c58;">Konfirmasi Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi password baru" required>
                </div>

                <button type="submit" class="btn w-100" style="background:#013c58; color:#fff;">Ubah Password</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="sosmed mb-2">
            <a href="https://www.instagram.com/accounts/login/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
            <a href="https://twitter.com/login" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
            <a href="https://www.facebook.com/login/" target="_blank" rel="noopener"><i class="fab fa-facebook"></i></a>
        </div>
        <span class="phone"><i class="fa fa-phone"></i> 0812-3456-7890</span>
        <div class="copyright">
            &copy; 2025 Barang Bekas Murah, semua hak dilindungi
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
