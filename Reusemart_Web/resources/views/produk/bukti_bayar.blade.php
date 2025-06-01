@include('outer.header')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Bukti Bayar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
        }
        img.bukti-img {
            max-width: 100%;
            margin-top: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="mb-4">Upload Bukti Bayar</h2>

        <p><strong>Transaksi ID:</strong> <span id="transaksi-id">{{ $ID_PEMBELIAN ?? '-' }}</span></p>

        <div id="produk-list">
            @if (isset($PRODUK) && count($PRODUK) > 0)
                <h5 class="mt-4">Detail Produk yang Dibeli:</h5>
                <ul class="list-group mb-4">
                    @foreach ($PRODUK as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item['NAMA_PRODUK'] ?? '-' }}
                            <span>Rp{{ number_format($item['HARGA'], 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p><em>Tidak ada data produk.</em></p>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif        
        <p id="ongkir-wrapper" style="display: none;"><strong>Ongkir:</strong> Rp<span id="ongkir"></span></p>
        <p id="diskon-wrapper" style="display: none; color : #070;"><strong>Diskon Poin: -</strong> Rp<span id="diskon"></span></p>
        <p><strong>Total Bayar:</strong> Rp<span id="total-bayar">{{ isset($TRANSAKSI_PEMBELIAN) ? number_format($TRANSAKSI_PEMBELIAN->TOTAL_BAYAR, 0, ',', '.') : '-' }}</span></p>
        
        <form id="upload-form" enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label for="bukti_bayar" class="form-label">Pilih File Bukti Bayar (gambar):</label>
                <input type="file" class="form-control" id="bukti_bayar" name="BUKTI_BAYAR" accept="image/*" required>
                @error('BUKTI_BAYAR')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Upload Bukti Bayar</button>
        </form> 

        @if(isset($TRANSAKSI_PEMBELIAN) && $TRANSAKSI_PEMBELIAN->BUKTI_BAYAR)
            <h5 class="mt-4">Bukti Bayar yang sudah diupload:</h5>
            <img src="{{ asset('storage/' . $TRANSAKSI_PEMBELIAN->BUKTI_BAYAR) }}" alt="Bukti Bayar" class="bukti-img">
        @endif
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const transaksiResult = JSON.parse(localStorage.getItem('transaksiResult'));
    console.log('Data transaksi dari localStorage:', transaksiResult);
    
    if (transaksiResult) {
        // console.log('Data transaksi dari halaman sebelumnya:', transaksiResult);

        const transaksiIdElem = document.getElementById('transaksi-id');
        if (transaksiResult.ID_TRANSAKSI_PEMBELIAN && transaksiIdElem) {
            transaksiIdElem.textContent = transaksiResult.ID_TRANSAKSI_PEMBELIAN;
        }

        const totalBayarElem = document.getElementById('total-bayar');
        if (transaksiResult.TRANSAKSI_PEMBELIAN && transaksiResult.TRANSAKSI_PEMBELIAN.TOTAL_BAYAR && totalBayarElem) {
            totalBayarElem.textContent = new Intl.NumberFormat('id-ID').format(transaksiResult.TRANSAKSI_PEMBELIAN.TOTAL_BAYAR - (transaksiResult.TRANSAKSI_PEMBELIAN.POIN_DISKON * 100));
        }

        const produkListContainer = document.getElementById('produk-list');
        
        if (transaksiResult.PRODUK && Array.isArray(transaksiResult.PRODUK) && produkListContainer) {
            let html = '<h5 class="mt-4">Detail Produk yang Dibeli:</h5><ul class="list-group mb-4">';
            transaksiResult.PRODUK.forEach(item => {
                const namaProduk = item.NAMA_PRODUK || '-';
                const harga = item.HARGA || 0;
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    ${namaProduk}
                    <span>Rp${harga.toLocaleString('id-ID')}</span>
                </li>`;
            });
            html += '</ul>';
            produkListContainer.innerHTML = html;
        }
        // Tambahkan pengecekan ongkir
        const ongkirWrapper = document.getElementById('ongkir-wrapper');
        const ongkirElem = document.getElementById('ongkir');

        if (
            transaksiResult.PRODUK &&
            Array.isArray(transaksiResult.PRODUK) &&
            transaksiResult.TRANSAKSI_PEMBELIAN
        ) {
            // Hitung total harga produk
            const totalHargaProduk = transaksiResult.PRODUK.reduce((acc, item) => acc + (item.HARGA || 0), 0);
            const totalBayar = transaksiResult.TRANSAKSI_PEMBELIAN.TOTAL_BAYAR;

            const ongkir = totalBayar > totalHargaProduk ? totalBayar - totalHargaProduk : 0;

            if (ongkir > 0 && ongkirElem && ongkirWrapper) {
                ongkirElem.textContent = ongkir.toLocaleString('id-ID');
                ongkirWrapper.style.display = 'block'; // tampilkan elemen ongkir
            }

        }
        const diskonWrapper = document.getElementById('diskon-wrapper');
        const diskonElem = document.getElementById('diskon');

        const poinDiskon = transaksiResult.TRANSAKSI_PEMBELIAN.POIN_DISKON || 0;
        if (poinDiskon > 0 && diskonElem && diskonWrapper) {
            const jumlahDiskon = poinDiskon * 100;
            diskonElem.textContent = jumlahDiskon.toLocaleString('id-ID');
            diskonWrapper.style.display = 'block';
        }
        
    } else {
        console.warn('Data transaksi tidak ditemukan di localStorage.');

        
    }

    const form = document.getElementById('upload-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const transaksiResult = JSON.parse(localStorage.getItem('transaksiResult')) || {};
        // console.log('PRODUK:', transaksiResult.PRODUK);

        const fileInput = form.querySelector('input[name="BUKTI_BAYAR"]');
        if (!fileInput.files.length) {
            alert('Mohon pilih file bukti bayar!');
            return;
        }

        const formData = new FormData();
        formData.append('BUKTI_BAYAR', fileInput.files[0]);
        if (transaksiResult.PRODUK) {
            formData.append('PRODUK', JSON.stringify(transaksiResult.PRODUK));
        }

        const idPembelian = transaksiResult.ID_PEMBELIAN;
        if (!idPembelian) {
            alert('ID Pembelian tidak ditemukan!');
            return;
        }

        fetch(`/api/upload-bukti/${idPembelian}`, {
            method: 'POST', 
            headers: {
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin' // kalau perlu cookie, tapi biasanya tidak perlu di api.php
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            localStorage.removeItem('transaksiResult');
            // Refresh atau redirect
            window.location.href = '/';
        })
        .catch(error => {
            console.error('Error response:', error.message);
            alert('Error saat upload bukti bayar. Cek console untuk detail.');
        });
    });

});
</script>

</body>
</html>
@include('outer.footer')
