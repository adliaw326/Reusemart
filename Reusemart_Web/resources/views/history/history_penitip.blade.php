@include('outer.header')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Riwayat Produk</title>
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">Riwayat Produk Penitipan <span id="kepunyaan"></span></h3>
        <table class="table table-bordered" id="produkTable">
            <thead class="table-primary">
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Berat (kg)</th>
                    <th>Harga (Rp)</th>
                    <th>Garansi</th>
                    <th>Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan dimasukkan di sini dengan JS -->
            </tbody>
        </table>
    </div>
</body>

<style>
html, body {
    height: 100%;
}
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('http://localhost:8000/api/produk_terkait_penitipan', {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Gagal mengambil data');

        const result = await response.json();
        const data = result.data;
        const pemilik = result.pemilik;

        const tbody = document.querySelector('#produkTable tbody');
        const kepunyaan = document.getElementById('kepunyaan');
        kepunyaan.innerText = pemilik;
        tbody.innerHTML = '';

        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.KODE_PRODUK}</td>
                <td>${item.NAMA_PRODUK}</td>
                <td>${item.KATEGORI}</td>
                <td>${item.BERAT}</td>
                <td>${item.HARGA}</td>
                <td>${item.GARANSI ?? '-'}</td>
                <td>${item.RATING ?? '-'}</td>
                <td>Tidak diketahui</td>
            `;
            tbody.appendChild(row);
        });

    } catch (error) {
        console.error(error);
        alert('Gagal memuat data produk penitipan');
    }
});
</script>

@include('outer.footer')