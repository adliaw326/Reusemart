<!-- filepath: resources/views/penitip/create_penitip.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penitip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'cs') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
        }
    </script>
    <style>
        body { background: #0b1e33; color: #fff; }
        .card { background: #013c58; color: #ffba42; }
        .form-label { color: #ffba42; }
        .btn-primary { background: #ffba42; color: #013c58; border: none; }
        .btn-primary:hover { background: #f5a201; color: #fff; }
        .form-control { background: #fff; color: #013c58; }
        .input-group-text { background-color: #ffba42; color: #013c58; border: none; cursor: pointer; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Tambah Penitip</h4>
                    <a href="/kelola_penitip" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="card-body">
                    <form id="formPenitip">
                        <div class="mb-3">
                            <label for="email_penitip" class="form-label">Email Penitip</label>
                            <input type="email" class="form-control" id="email_penitip" name="email_penitip" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_penitip" class="form-label">Password Penitip</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_penitip" name="password_penitip" required>
                                <span class="input-group-text" onclick="togglePassword('password_penitip')"><i class="fa fa-eye" id="eye_password_penitip"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="repassword_penitip" class="form-label">Ulangi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="repassword_penitip" name="repassword_penitip" required>
                                <span class="input-group-text" onclick="togglePassword('repassword_penitip')"><i class="fa fa-eye" id="eye_repassword_penitip"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="nama_penitip" class="form-label">Nama Penitip</label>
                            <input type="text" class="form-control" id="nama_penitip" name="nama_penitip" required>
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </form>
                    <div id="alertBox" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const eyeIcon = document.getElementById('eye_' + fieldId);
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>
