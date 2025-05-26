@include('outer.header')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Alamat Pemilik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <style>
        .default-badge {
            font-size: 0.8rem;
            background-color: #ffc107;
            color: #000;
            border-radius: 4px;
            padding: 2px 6px;
            margin-left: 8px;
        }
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<!-- Modal Edit Alamat -->
<div class="modal fade" id="modalEditAlamat" tabindex="-1" aria-labelledby="modalEditAlamatLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditAlamat" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditAlamatLabel">Edit Alamat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_id_alamat" name="id_alamat">
        <div class="mb-3">
          <label for="edit_lokasi" class="form-label">Lokasi</label>
          <input type="text" class="form-control" id="edit_lokasi" name="lokasi" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalTambahAlamat" tabindex="-1" aria-labelledby="modalTambahAlamatLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formTambahAlamat" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahAlamatLabel">Tambah Alamat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="lokasi" class="form-label">Lokasi</label>
          <input type="text" class="form-control" id="lokasi" name="lokasi" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Alamat</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahAlamat">+ Tambah Alamat</button>
        </div>
        <div id="alamat-list" class="list-group">
            <div class="text-muted">Memuat data...</div>
        </div>
    </div>

    <script>
        const pemilik = "PB001";
        const apiUrl = `http://127.0.0.1:8000/api/alamat/find/${pemilik}`;
        const container = document.getElementById('alamat-list');

        function loadData() {
            fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = '<p class="text-muted">Tidak ada alamat ditemukan.</p>';
                    return;
                }

                data.forEach(alamat => {
                    const div = document.createElement('div');
                    div.className = 'list-group-item d-flex justify-content-between align-items-center';

                    const alamatHTML = `
                        <div>
                            <strong>${alamat.LOKASI}</strong>
                            ${alamat.STATUS_DEFAULT == 1 ? '<span class="default-badge">Alamat Utama</span>' : ''}
                        </div>
                        <div>
                            <a href="#" class="btn btn-sm btn-primary me-1" onclick="bukaModalEdit('${alamat.ID_ALAMAT}', '${alamat.LOKASI}')">Edit</a>
                            <button class="btn btn-sm btn-danger me-1" onclick="hapusAlamat('${alamat.ID_ALAMAT}')">Hapus</button>
                            ${alamat.STATUS_DEFAULT != 1 ? `<button class="btn btn-sm btn-warning" onclick="jadikanDefault('${alamat.ID_ALAMAT}')">Jadikan Default</button>` : ''}
                        </div>
                    `;
                    div.innerHTML = alamatHTML;
                    container.appendChild(div);
                });
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = '<div class="text-danger">Gagal memuat data alamat.</div>';
            });
        }
        function bukaModalEdit(id, lokasi) {
            // Isi form edit
            document.getElementById('edit_id_alamat').value = id;
            document.getElementById('edit_lokasi').value = lokasi;

            // Tampilkan modal edit
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEditAlamat'));
            modalEdit.show();
        }


        document.getElementById('formTambahAlamat').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const lokasi = document.getElementById('lokasi').value;

            fetch('http://127.0.0.1:8000/api/alamat/tambah', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ID_PEMILIK: pemilik,
                    LOKASI: lokasi
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success || result.status === 'success') {
                    alert('Alamat berhasil ditambahkan!');
                    document.getElementById('formTambahAlamat').reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahAlamat'));
                    modal.hide();
                    loadData();
                } else {
                    alert(result.message || 'Gagal menambahkan alamat.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menambahkan alamat.');
            });
        });

        function hapusAlamat(id) {
            if (confirm('Yakin ingin menghapus alamat ini?')) {
                fetch(`http://127.0.0.1:8000/api/alamat/hapus/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(result => {
                    alert(result.message || 'Alamat dihapus.');
                    loadData();
                })
                .catch(() => alert('Gagal menghapus alamat.'));
            }
        }

        function jadikanDefault(id) {
            fetch(`http://127.0.0.1:8000/api/alamat/default/${id}`, {
                method: 'PUT',
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(result => {
                alert(result.message || 'Alamat diatur sebagai default.');
                loadData();
            })
            .catch(() => alert('Gagal mengubah status default.'));
        }

        document.getElementById('formEditAlamat').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('edit_id_alamat').value;
            const lokasi = document.getElementById('edit_lokasi').value;


            fetch(`http://127.0.0.1:8000/api/alamat/update/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ LOKASI: lokasi })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success || result.status === 'success') {
                    alert('Alamat berhasil diperbarui!');
                    const modalEdit = bootstrap.Modal.getInstance(document.getElementById('modalEditAlamat'));
                    modalEdit.hide();
                    loadData();
                } else {
                    alert(result.message || 'Gagal memperbarui alamat.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat memperbarui alamat.');
            });
        });


        loadData();
    </script>
</body>
</html>
@include('outer.footer')
