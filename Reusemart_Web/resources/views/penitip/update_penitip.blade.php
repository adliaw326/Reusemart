<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Penitip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const user = JSON.parse(sessionStorage.getItem('pegawai'));
            if (!user || user.ID_ROLE !== 'RL001') {
                alert('Anda tidak memiliki akses ke halaman ini.');
                window.location.href = '/admin/dashboard';
            }

            const penitipId = window.location.pathname.split('/').pop();

            fetch(`http://localhost:8000/api/penitip/${penitipId}`)
            .then(res => {
                console.log('Response status:', res.status);
                return res.json();
            })
            .then(data => {
                console.log(data);
                const p = data;
                document.getElementById('id_penitip').value = p.ID_PENITIP;
                document.getElementById('email_penitip').value = p.EMAIL_PENITIP;
                document.getElementById('nama_penitip').value = p.NAMA_PENITIP;
                document.getElementById('nik').value = p.NIK;  // tetap tampil di form, tapi tidak dikirim saat update
            })
            .catch(() => alert('Gagal memuat data penitip.'));
        });
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
                            <input type="text" class="form-control" id="nik" disabled>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                    </form>
                    <div id="alertBox" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('formUpdatePenitip').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('id_penitip').value;
        const data = {
            email_penitip: document.getElementById('email_penitip').value,
            nama_penitip: document.getElementById('nama_penitip').value
            // NIK tidak dikirim karena tidak update
        };

        fetch(`http://localhost:8000/api/penitip/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            const box = document.getElementById('alertBox');
            if (res.success) {
                box.innerHTML = '<div class="alert alert-success">Berhasil diupdate!</div>';
                window.location.href = '/kelola_penitip';
            } else {
                box.innerHTML = '<div class="alert alert-danger">' + (res.message || 'Update gagal') + '</div>';
            }
        })
        .catch(() => {
            document.getElementById('alertBox').innerHTML = '<div class="alert alert-danger">Server error</div>';
        });
    });
</script>
</body>
</html>
