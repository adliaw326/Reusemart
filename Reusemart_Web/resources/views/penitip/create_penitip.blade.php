<!-- filepath: resources/views/penitip/create_penitip.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penitip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
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
                                <label for="no_telp_penitip" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="no_telp_penitip" name="no_telp_penitip" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </form>
                        <div id="alertBox" class="mt-3"></div>

                        <!-- Modal Konfirmasi -->
                        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="color:#000;">
                              <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Simpan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                Apakah Anda yakin ingin menambahkan penitip baru?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary" id="confirmSaveBtn">Ya, Simpan</button>
                              </div>
                            </div>
                          </div>
                        </div>

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

        function showAlert(message, type = 'success') {
            const alertBox = document.getElementById('alertBox');
            alertBox.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }

        // Event submit form -> tampilkan modal
        document.getElementById('formPenitip').addEventListener('submit', function (e) {
            e.preventDefault();

            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
        });

        // Event klik tombol simpan di modal
        document.getElementById('confirmSaveBtn').addEventListener('click', function () {
            const confirmModalEl = document.getElementById('confirmModal');
            const confirmModal = bootstrap.Modal.getInstance(confirmModalEl);
            confirmModal.hide();

            const token = localStorage.getItem('token');

            const password = document.getElementById('password_penitip').value;
            const repassword = document.getElementById('repassword_penitip').value;

            // Validasi password dan ulangi password sama
            if (password !== repassword) {
                showAlert('Password dan Ulangi Password tidak sama.', 'danger');
                return;
            }

            const data = {
                email_penitip: document.getElementById('email_penitip').value,
                password_penitip: password,
                nama_penitip: document.getElementById('nama_penitip').value,
                nik: document.getElementById('nik').value,
                no_telp_penitip: document.getElementById('no_telp_penitip').value,
                alamat: document.getElementById('alamat').value,
            };

            fetch('/api/penitip/create', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.message || 'Terjadi kesalahan');
                    });
                }
                return response.json();
            })
            .then(result => {
                alert('Penitip berhasil dibuat!');
                window.location.href = '/kelola_penitip';
            })
            .catch(error => {
                showAlert('Gagal membuat penitip: ' + error.message, 'danger');
            });
        });
    </script>

    <!-- Bootstrap JS (modal butuh ini) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
