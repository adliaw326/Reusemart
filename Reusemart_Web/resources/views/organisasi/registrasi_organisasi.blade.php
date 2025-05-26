<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <style>
        body {
            min-height: 100vh;
            background: #fff; /* Background putih */
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
            background-color: #fffaec; /* Warna input menyesuaikan tema kuning muda */
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
    <div class="header">
        Reusemart
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="login-container">
            <h2 class="mb-4 text-center" style="color:#0b1e33;">Registrasi Organisasi</h2>
            <div id="notification" style="display:none; margin-bottom: 15px; padding: 12px; border-radius: 5px; font-weight: 600;"></div>
            <form id="register-form">
                <!-- @csrf --> <!-- bisa dihapus kalau ini plain HTML -->
                <div class="mb-3 text-start">
                    <label for="nama" class="form-label" style="color:#013c58;">Nama Organisasi</label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan nama Organisasi" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="email" class="form-label" style="color:#013c58;">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label" style="color:#013c58;">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn w-100" style="background:#013c58; color:#fff;">Register</button>
            </form>
            <div class="register-link mt-3 text-center" style="color:#013c58;">
                Sudah punya akun? <a href="#" style="color:#00537a;text-decoration:none;">Login</a>
            </div>
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
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('register-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const notification = document.getElementById('notification');
        const form = e.target;
        const formData = new FormData(form);

        const data = {};
        formData.forEach((value, key) => (data[key] = value));

        function showNotification(message, type = 'success') {
            notification.style.display = 'block';
            notification.textContent = message;
            if (type === 'success') {
                notification.style.backgroundColor = '#d4edda';
                notification.style.color = '#155724';
                notification.style.border = '1px solid #c3e6cb';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#f8d7da';
                notification.style.color = '#721c24';
                notification.style.border = '1px solid #f5c6cb';
            }
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }

        try {
            const response = await fetch('http://127.0.0.1:8000/api/organisasi/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                await response.json(); // Baca agar error tidak muncul, abaikan isinya
                showNotification('Pendaftaran gagal', 'error');
                return;
            }

            await response.json(); // Bisa dipakai jika suatu saat ingin ambil token
            showNotification('Pendaftaran sukses', 'success');
            form.reset();

            setTimeout(() => {
                window.location.href = window.location.origin + '/login';
            }, 1000);
        } catch (error) {
            console.error(error);
            showNotification('Pendaftaran gagal', 'error');
        }
    });
</script>
</body>

</html>
