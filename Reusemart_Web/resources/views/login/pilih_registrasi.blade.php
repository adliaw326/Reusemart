<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        body {
            min-height: 100vh;
            background: #fff; /* Background putih */
            padding-top: 60px; /* ruang agar header fixed tidak menutupi konten */
            padding-bottom: 40px; /* ruang untuk footer jika ada */
        }
        .header {
            width: 100%;
            background-color: #013c58;
            color: #fff;
            padding: 18px 30px;
            font-size: 22px;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .login-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
            max-width: 400px;
            width: 100%;
            margin: 0 15px; /* beri margin horizontal untuk layar kecil */
        }
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1 0 auto;
        }
        /* Responsive tweaks */
        @media (max-width: 480px) {
            .header {
                font-size: 18px;
                padding: 14px 20px;
                text-align: center;
            }
            .login-container {
                padding: 24px 20px 20px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        Reusemart
    </div>
    <div class="d-flex justify-content-center align-items-center min-vh-100 main-content">
        <div class="login-container">
            <h2 class="mb-4 text-center" style="color:#0b1e33;">Pilih Registrasi</h2>
            <div class="d-grid gap-3">
                <a href="/registrasi/pembeli" class="btn btn-warning fw-bold" style="color:#013c58;">
                    Registrasi Pembeli
                </a>
                <a href="/registrasi/organisasi" class="btn" style="background:#013c58; color:#fff; font-weight:bold;">
                    Registrasi Organisasi
                </a>
            </div>
        </div>
    </div>    
    @include('outer.footer')
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
