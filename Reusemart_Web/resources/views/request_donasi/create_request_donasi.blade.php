<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Request Donasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #0b1e33;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card-white {
            background: #fff;
            color: #000;
            border-radius: 12px;
            padding: 20px;
        }
        .form-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>

    @include('outer.header')

    <div class="container form-container">
        <div class="col-md-8">
            <div class="card card-white shadow">
                <h4 class="mb-4 text-center">Buat Request Donasi</h4>
                <form id="requestForm">
                    <div class="mb-3">
                        <label for="ID_REQUEST" class="form-label">ID Request</label>
                        <input type="text" class="form-control" id="ID_REQUEST" required>
                    </div>

                    <div class="mb-3">
                        <label for="ID_PRODUK" class="form-label">Pilih Produk</label>
                        <select class="form-select" id="ID_PRODUK" required>
                            <option value="">-- Pilih Produk --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="DETAIL_REQUEST" class="form-label">Detail Request</label>
                        <textarea class="form-control" id="DETAIL_REQUEST" rows="3" required></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Request
                        </button>
                        <a href="/kelola_request_donasi" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('outer.footer')

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const user = JSON.parse(sessionStorage.getItem('organisasi'));
            if (!user || !user.ID_ORGANISASI) {
                alert('Organisasi tidak ditemukan, silakan login ulang.');
                window.location.href = '/login';
                return;
            }

            // Fetch produk yang bisa dipilih
            const res = await fetch('http://localhost:8000/api/produk/product_available');
            const produkList = await res.json();
            console.log(produkList); // Tambahkan ini

            if (!Array.isArray(produkList)) {
                alert('Data produk tidak valid.');
                console.error('Respon produk:', produkList);
                return;
            }

            const dropdown = document.getElementById('ID_PRODUK');
            produkList.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.ID_PRODUK;
                opt.textContent = `${p.NAMA_PRODUK} (${p.KATEGORI})`;
                dropdown.appendChild(opt);
            });

            // Submit form
            document.getElementById('requestForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                const body = {
                    ID_REQUEST: document.getElementById('ID_REQUEST').value,
                    ID_ORGANISASI: user.ID_ORGANISASI,
                    ID_PRODUK: document.getElementById('ID_PRODUK').value,
                    DETAIL_REQUEST: document.getElementById('DETAIL_REQUEST').value,
                };

                const response = await fetch('http://localhost:8000/api/request_donasi', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });

                const result = await response.json();
                if (response.ok) {
                    alert('Request berhasil dibuat.');
                    window.location.href = '/kelola_request_donasi';
                } else {
                    alert(result.message || 'Gagal menyimpan request.');
                }
            });
        });
    </script>

</body>
</html>
