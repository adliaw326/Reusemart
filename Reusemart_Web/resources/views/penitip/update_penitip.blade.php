<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Penitip</title>
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Update Penitip</h4>
                        <a href="/kelola_penitip" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="card-body">
                        <form id="formUpdatePenitip">
                            <input type="hidden" id="id_penitip">
                            <div class="mb-3">
                                <label for="email_penitip" class="form-label">Email Penitip</label>
                                <input type="email" class="form-control" id="email_penitip" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_penitip" class="form-label">Nama Penitip</label>
                                <input type="text" class="form-control" id="nama_penitip" required>
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_telp_penitip" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="no_telp_penitip" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="showConfirmModal">
                                <i class="fa fa-save"></i> Update
                            </button>
                        </form>
                        <!-- Modal Konfirmasi Update -->
                        <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark text-white">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmUpdateLabel">Konfirmasi Update</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin memperbarui data ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary" id="confirmUpdateBtn">Ya, Update</button>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div id="alertBox" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('token');
            const id = "{{ $id_penitip }}";

            // Form elements
            const emailInput = document.getElementById('email_penitip');
            const namaInput = document.getElementById('nama_penitip');
            const nikInput = document.getElementById('nik');
            const telpInput = document.getElementById('no_telp_penitip');
            const alamatInput = document.getElementById('alamat');
            const alertBox = document.getElementById('alertBox');

            // Ambil data awal
            fetch(`/api/penitip/${id}`, {
                headers: { 'Authorization': 'Bearer ' + token }
            })
            .then(res => res.json())
            .then(data => {
                emailInput.value = data.EMAIL_PENITIP;
                namaInput.value = data.NAMA_PENITIP;
                nikInput.value = data.NIK;
                telpInput.value = data.NO_TELP_PENITIP;
                alamatInput.value = data.alamat_default ? data.alamat_default.LOKASI : '';
            })
            .catch(() => {
                alertBox.innerHTML = `<div class="alert alert-danger">Gagal mengambil data penitip</div>`;
            });

            // Tampilkan modal konfirmasi saat tombol update diklik
            document.getElementById('showConfirmModal').addEventListener('click', function () {
                const modal = new bootstrap.Modal(document.getElementById('confirmUpdateModal'));
                modal.show();
            });

            // Saat user menekan "Ya, Update"
            document.getElementById('confirmUpdateBtn').addEventListener('click', function () {
                const payload = {
                    email_penitip: emailInput.value,
                    nama_penitip: namaInput.value,
                    nik: nikInput.value,
                    no_telp_penitip: telpInput.value,
                    alamat: alamatInput.value
                };

                fetch(`/api/penitip/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message) {
                        alertBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        alert('Penitip berhasil diubah!');
                        window.location.href = '/kelola_penitip';
                    } else {
                        alertBox.innerHTML = `<div class="alert alert-danger">Gagal update penitip</div>`;
                    }
                })
                .catch(() => {
                    alertBox.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan saat update</div>`;
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
