@include('outer.header')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
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
        }
    </style>
</head>
<body class="bg-light">
        <div class="container py-5">
            <h2 class="mb-4">Daftar Produk</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Berat</th>
                            <th>Garansi(tanggal)</th>
                            <th class="text-end">Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody id="produk-body">
                        <tr><td colspan="6" class="text-center text-muted">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
            <form id="formMetodePengiriman" class="mt-3 d-flex align-items-center gap-3">
                <label class="mb-0 fw-semibold">Pilih Metode Pengantaran :</label>

                <label class="form-check-label" for="pickup">
                    <input type="radio" class="form-check-input ms-2" name="metode_pengiriman" id="pickup" value="Pick Up" required>
                    Pick Up
                </label>

                <label class="form-check-label" for="delivery">
                    <input type="radio" class="form-check-input ms-2" name="metode_pengiriman" id="delivery" value="Delivery" required>
                    Delivery
                </label>

            </form>
            <div id="ringkasan-pembelian" class="mt-4 d-flex justify-content-between align-items-center p-3 border rounded bg-white">
                <div>
                    <div>
                        <p class="mb-1 fw-semibold">Total Harga: <span id="total-harga-produk">Rp 0</span></p>
                        <p class="mb-0 fw-semibold" id="ongkir-text" style="display: none;">Ongkir: <span id="ongkir">Rp 10.000</span></p>
                        <p id="diskon-text" class="text-success" style="display: none;"></p>
                        <p class="mb-1 fw-semibold">Total Bayar: <span id="total-harga">Rp 0</span></p>
                        <p class="mb-0 text-primary" id="poin-didapat" style="font-weight: 600;">Poin yang bisa didapat: 0</p>
                    </div>
                    <div class="mt-3 d-flex align-items-center gap-3">
                        <label for="input-poin" class="mb-0 fw-semibold">Gunakan Poin (diskon Rp10.000 per 100 poin):</label>
                        <input type="number" id="input-poin" class="form-control" style="width: 150px;" min="0" step="100" value="0">
                        <span id="sisa-poin" class="badge bg-primary p-2">Sisa Poin: 0</span>
                    </div>
                </div>
                <button id="checkout-btn" class="btn btn-success">Checkout</button>
            </div>
        </div>

        <div class="modal fade" id="modalPilihAlamat" tabindex="-1" aria-labelledby="modalPilihAlamatLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihAlamatLabel">Pilih Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="alamat-list">
                    <p class="text-muted">Memuat daftar alamat...</p>
                </div>
                </div>
            </div>
        </div>

    <script>

        const totalHargaElem = document.getElementById('total-harga');    
        const totalProdukElem = document.getElementById('total-harga-produk');    
        const ongkirTextElem = document.getElementById('ongkir-text');
        const checkoutBtn = document.getElementById('checkout-btn');        

        const deliveryRadio = document.getElementById('delivery');
        const pickupRadio = document.getElementById('pickup');

        const btnPilihAlamat = document.createElement('button');
        const alamatTerpilih = document.createElement('span');

        // Buat div container alamat dan button pilih alamat
        const formMetode = document.getElementById('formMetodePengiriman');
        const alamatContainer = document.createElement('div');

        const userId = localStorage.getItem('userId');

        const apiProduk = `http://127.0.0.1:8000/api/keranjang/${userId}`;
        const apiAlamat = `http://127.0.0.1:8000/api/alamat/${userId}`;
        const tbody = document.getElementById('produk-body');

        const poinInput = document.getElementById('input-poin');
        const diskonTextElem = document.getElementById('diskon-text');

        let totalHarga = 0; // DEKLARASI GLOBAL

        let ongkir = 100000; // Ongkir tetap Rp 10.000
        
        let poinTersedia = 0; // nilai poin dari API
        let poinDigunakan = 0;

        const modalAlamat = new bootstrap.Modal(document.getElementById('modalPilihAlamat'));
        const alamatListContainer = document.getElementById('alamat-list');        
        const produkList = [];

        function updateRingkasan() {
                let total = totalHarga;

                if (deliveryRadio.checked) {
                    ongkirTextElem.style.display = 'block';
                    if( total >= 1500000) {
                        ongkir = 0; // gratis ongkir jika total >= 1.500.000
                    } else {
                        ongkir = 100000; // ongkir tetap Rp 100.000
                    }
                    ongkirTextElem.textContent = `Ongkir: Rp ${ongkir.toLocaleString()}`;
                    total += ongkir;
                } else {
                    ongkirTextElem.style.display = 'none';
                    ongkirTextElem.textContent = '';
                }
                
                poinDigunakan = parseInt(poinInput.value) || 0;
                if (poinDigunakan > poinTersedia) poinDigunakan = poinTersedia;
                if (poinDigunakan % 100 !== 0) poinDigunakan -= poinDigunakan % 100;

                const potongan = poinDigunakan * 100; // setiap 100 poin diskon Rp 100
                total -= potongan; // kurangi poin dari total
                total = Math.max(total, 0); 
                
                if (poinDigunakan > 0) {
                    diskonTextElem.style.display = 'block';
                    diskonTextElem.textContent = `Diskon dari poin: -Rp ${potongan.toLocaleString()}`;
                } else {
                    diskonTextElem.style.display = 'none';
                    diskonTextElem.textContent = '';
                }

                const poinTersisa = poinTersedia - poinDigunakan;
                document.getElementById('sisa-poin').textContent = `Sisa Poin: ${poinTersisa.toLocaleString()}`;

                totalHargaElem.textContent = `Total: Rp ${total.toLocaleString()}`;
                let poinDidapat = Math.floor(total / 10000);
                if (total > 500000) {
                    poinDidapat += Math.floor(poinDidapat * 0.2);
                }

                document.getElementById('poin-didapat').textContent = `Poin yang bisa didapat: ${poinDidapat.toLocaleString()}`;
                }

            function toggleAlamatContainer() {
                if (deliveryRadio.checked) {
                    alamatContainer.style.display = 'flex'; // tampilkan alamat
                    alamatTerpilih.textContent = alamatDefault; // nanti ganti dengan data alamat user
                } else {
                    alamatContainer.style.display = 'none'; // sembunyikan saat pick up
                }
            }

        async function fetchAlamatDefault() {
            try {
                const response =  await fetch(apiAlamat, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        // Tambahkan header auth kalau perlu
                    },
                });

                if (!response.ok) throw new Error('Gagal mengambil data alamat');

                const data = await response.json();

                // data.alamat diasumsikan array alamat dari server
                if (data.alamat && data.alamat.length > 0) {
                    // cari alamat dengan STATUS_DEFAULT = 1
                    const alamatDefault = data.alamat.find(a => a.STATUS_DEFAULT == 1);
                    if (alamatDefault) {
                        return alamatDefault.LOKASI;
                    }
                }
                return 'BUDI'; // fallback jika tidak ditemukan
            } catch (error) {
                console.error(error);
                return 'Skidi'; // fallback jika error fetch
            }
        }

        async function fetchPoinPembeli() {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/pembeli/show/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('Gagal mengambil data poin pembeli');

                const data = await res.json();
                poinTersedia = parseInt(data.POIN_PEMBELI) || 0;
                document.getElementById('sisa-poin').textContent = `Sisa Poin: ${poinTersedia.toLocaleString()}`;
            } catch (error) {
                console.error(error);
                document.getElementById('sisa-poin').textContent = 'Sisa Poin: -';
            }
        }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function loadProduk() {
            fetch(apiProduk)
            .then(res => res.json())
            .then(response => {
                tbody.innerHTML = '';
                console.log(response);
                const data = response.data;
                totalHarga = 0; // Reset sebelum menghitung ulang

                if (!data || data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Tidak ada produk tersedia.</td></tr>';
                    updateRingkasan(); // tetap update meskipun kosong
                    return;
                }

                data.forEach(item => {
                    const produk = item.produk;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${produk.NAMA_PRODUK}</td>
                        <td>${produk.KATEGORI}</td>
                        <td>${produk.BERAT}</td>
                        <td>${produk.GARANSI ? produk.GARANSI : 'Tidak ada'}</td>
                        <td class="text-end">Rp ${parseInt(produk.HARGA).toLocaleString()}</td>
                    `;
                    tbody.appendChild(row);

                    // Tambahkan harga produk ke total
                    totalHarga += parseInt(produk.HARGA);

                    produkList.push({
                        ID_PRODUK: produk.ID_PRODUK, // pastikan ID tersedia
                        NAMA_PRODUK: produk.NAMA_PRODUK,
                        KATEGORI: produk.KATEGORI,
                        BERAT: produk.BERAT,
                        GARANSI: produk.GARANSI,
                        HARGA: parseInt(produk.HARGA)
                    });
                });
                totalProdukElem.textContent = `Rp ${totalHarga.toLocaleString()}`;
                updateRingkasan(); // update tampilan total harga dan ongkir

            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data produk.</td></tr>';
                updateRingkasan(); // tetap update jika error
            });
        }           
        
        document.getElementById('input-poin').addEventListener('input', function () {
            let val = parseInt(this.value) || 0;

            // Batasi kelipatan 100
            if (val % 100 !== 0) {
                val = Math.floor(val / 100) * 100;
            }

            // Batasi tidak melebihi poin tersedia
            if (val > poinTersedia) {
                val = poinTersedia - (poinTersedia % 100); // dibulatkan ke bawah
            }

            this.value = val;
            updateRingkasan(); // akan otomatis hitung ulang diskon & sisa poin
        });
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        

        document.addEventListener('DOMContentLoaded', async function() {
            function toggleAlamatContainer() {
                if (deliveryRadio.checked) {
                    alamatContainer.style.display = 'flex'; // tampilkan alamat
                    alamatTerpilih.textContent = alamatDefault; // nanti ganti dengan data alamat user
                } else {
                    alamatContainer.style.display = 'none'; // sembunyikan saat pick up
                }
            }            
            loadProduk();
            await fetchPoinPembeli();
            document.getElementById('input-poin').addEventListener('input', updateRingkasan);

            // Elements terkait metode pengiriman
            // const deliveryRadio = document.getElementById('delivery');
            // const pickupRadio = document.getElementById('pickup');

            // // Buat div container alamat dan button pilih alamat
            // const formMetode = document.getElementById('formMetodePengiriman');
            // const alamatContainer = document.createElement('div');
            alamatContainer.id = 'alamat-container';
            alamatContainer.style.display = 'none'; // default hidden
            alamatContainer.style.marginLeft = '20px';
            alamatContainer.style.alignItems = 'center';
            alamatContainer.style.gap = '10px';
            alamatContainer.style.display = 'flex';

            // Button pilih alamat
            // const btnPilihAlamat = document.createElement('button');
            btnPilihAlamat.type = 'button';
            btnPilihAlamat.className = 'btn btn-primary btn-sm';
            btnPilihAlamat.textContent = 'Pilih Alamat';

            // Span alamat terpilih (default placeholder)
            // const btnPilihAlamat = document.createElement('button');
            // const alamatTerpilih = document.createElement('span');
            alamatTerpilih.id = 'alamat-terpilih';
            alamatTerpilih.textContent = '-';

            alamatContainer.appendChild(btnPilihAlamat);
            alamatContainer.appendChild(alamatTerpilih);

            formMetode.appendChild(alamatContainer);
            
            const alamatDefault = await fetchAlamatDefault();
            // console.log(`Alamat default: ${alamatDefault}`); 
            alamatTerpilih.textContent = alamatDefault;

            // Pasang event listener radio button
            deliveryRadio.addEventListener('change', () => {
                toggleAlamatContainer();
                updateRingkasan();
            });
            pickupRadio.addEventListener('change', () => {
                toggleAlamatContainer();
                updateRingkasan();
            });

            // Jalankan sekali saat load untuk cek status radio
            toggleAlamatContainer();

            // Event contoh klik tombol pilih alamat (bisa kamu kembangkan)
            // Tombol "Pilih Alamat" diklik
            btnPilihAlamat.addEventListener('click', async () => {
                alamatListContainer.innerHTML = '<p class="text-muted">Memuat daftar alamat...</p>';

                try {
                    const res = await fetch(apiAlamat, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!res.ok) throw new Error('Gagal memuat alamat');

                    const data = await res.json();

                    if (!data.alamat || data.alamat.length === 0) {
                        alamatListContainer.innerHTML = '<p class="text-danger">Tidak ada alamat tersedia.</p>';
                        return;
                    }

                    // Tampilkan semua alamat sebagai button
                    alamatListContainer.innerHTML = '';
                    data.alamat.forEach(item => {
                        const btn = document.createElement('button');
                        btn.className = 'btn btn-outline-secondary d-block w-100 text-start mb-2';
                        btn.textContent = item.LOKASI;
                        btn.addEventListener('click', () => {
                            alamatTerpilih.textContent = item.LOKASI;
                            modalAlamat.hide();
                        });
                        alamatListContainer.appendChild(btn);
                    });

                    modalAlamat.show();
                } catch (err) {
                    console.error(err);
                    alamatListContainer.innerHTML = '<p class="text-danger">Terjadi kesalahan saat memuat alamat.</p>';
                }
            });                
        });

        checkoutBtn.addEventListener('click', async () => {
            // Data yang dibutuhkan
            const ID_PEMBELI = userId;
            const STATUS_TRANSAKSI = 'BELUM DIBAYAR'; // default status
            const STATUS_PENGIRIMAN = deliveryRadio.checked ? 'delivery' : 'pickup';
            const TANGGAL_PESAN = new Date().toISOString().split('T')[0]; // YYYY-MM-DD
            const TANGGAL_LUNAS = null;
            const TANGGAL_KIRIM = deliveryRadio.checked ? null : null; // bisa isi logic lebih spesifik
            const TANGGAL_SAMPAI = deliveryRadio.checked ? null : null;
            const STATUS_RATING = 'BELUM';
            const SISA_POIN = document.getElementById('sisa-poin').textContent;
            const POIN_DIDAPAT = document.getElementById('poin-didapat').textContent;                  
            const PRODUK = produkList;
            const BUKTI_BAYAR = null;
            const TOTAL_BAYAR = document.getElementById('total-harga').textContent;

            // Kirim POST ke route transaksi
            try {
                const res = await fetch('/api/transaksi-pembelian', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // jika Laravel pakai CSRF
                    },
                    body: JSON.stringify({
                        ID_PEMBELI,
                        STATUS_TRANSAKSI,
                        TANGGAL_PESAN,
                        TANGGAL_LUNAS,
                        TANGGAL_KIRIM,
                        TANGGAL_SAMPAI,
                        STATUS_PENGIRIMAN,
                        STATUS_RATING,
                        SISA_POIN,
                        POIN_DIDAPAT,
                        TOTAL_BAYAR
                    })
                    ////INI BELUM BENER KONTOL
                });

                if (!res.ok) {
                    const errData = await res.json();
                    console.error('Gagal checkout:', errData);
                    alert('Gagal melakukan checkout. Periksa data dan coba lagi.');
                    return;
                }

                const responseData = await res.json();

                // Redirect atau tampilkan data ke halaman bukti bayar
                if (responseData.success) {
                    const ID_PEMBELIAN = responseData.ID_PEMBELIAN;

                    // Redirect ke halaman bukti bayar
                    window.location.href = `/bukti-bayar/${ID_PEMBELIAN}`;
                } else {
                    alert('Terjadi kesalahan saat menyimpan transaksi');
                }

            } catch (err) {
                console.error('Error:', err);
                alert('Terjadi kesalahan saat melakukan checkout.');
            }
        });

    </script>
</body>
</html>
@include('outer.footer')
