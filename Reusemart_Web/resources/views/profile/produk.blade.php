@include('outer.header')

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Produk</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        body {
            background-color: #f9f9f9;
            color: #0b1e33;
        }
        .product-container {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .label-info {
            font-weight: bold;
            color: #00537a;
        }
        .btn-cart {
            background-color: #f5a201;
            border: none;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 5px;
        }
        .btn-cart:hover {
            background-color: #ffba42;
            color: #013c58;
        }
        .discussion-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="product-container row">
            <div class="col-md-6">
                <img id="productImage" src="{{ asset('images/Elektronik.webp') }}" class="product-image" alt="Gambar Produk">
            </div>
            <div class="col-md-6">
                <h3 id="namaProduk">Nama Produk</h3>
                <p><span class="label-info">Kategori:</span> <span id="kategori"></span></p>
                <p><span class="label-info">Berat:</span> <span id="berat"></span> kg</p>
                <p><span class="label-info">Harga:</span> Rp <span id="harga"></span></p>
                <p><span class="label-info">Garansi:</span> <span id="garansi"></span></p>
                <p><span class="label-info">Rating:</span> <span id="rating"></span></p>
                <p><span class="label-info">Status:</span> Tidak diketahui</p>
                <button class="btn-cart mt-3">Add to Cart <i class="fas fa-cart-plus"></i></button>
            </div>
        </div>

        <div class="discussion-box">
            <h4>Forum Diskusi</h4>
            <!-- Form komentar utama -->
            <form id="discussionForm">
                <div class="mb-3">
                    <textarea class="form-control" id="comment" rows="3" placeholder="Tulis komentar..."></textarea>
                    <input type="hidden" id="idParent" value="" />

                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
            <!-- List komentar -->
            <ul class="mt-4 list-unstyled" id="commentsList"></ul>
        </div>
    </div>

    <script>
        const kodeProduk = 'H01';  // Kode produk yang digunakan di seluruh script

        // 1. Fetch data produk dan tampilkan di UI
        fetch(`http://127.0.0.1:8000/api/produk/find/${kodeProduk}`)
            .then(response => {
                if (!response.ok) throw new Error('Produk tidak ditemukan');
                return response.json();
            })
            .then(produk => {
                document.getElementById('namaProduk').innerText = produk.NAMA_PRODUK ?? '-';
                document.getElementById('kategori').innerText = produk.KATEGORI ?? '-';
                document.getElementById('berat').innerText = produk.BERAT ?? '0';
                document.getElementById('harga').innerText = produk.HARGA ?? '0';
                document.getElementById('garansi').innerText = produk.GARANSI ?? 'Tidak ada';
                document.getElementById('rating').innerText = produk.RATING ?? '-';
            })
            .catch(error => {
                console.error('Error:', error);
                // Tampilkan pesan error di UI jika perlu
            });


        // 2. Fetch data diskusi untuk produk ini dan render ke UI
        function fetchDiskusi() {
            fetch(`http://127.0.0.1:8000/api/diskusiProduk/${kodeProduk}`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal memuat diskusi');
                    return res.json();
                })
                .then(diskusiData => {
                    // Kosongkan dulu list komentar
                    list.innerHTML = '';
                    // Render komentar (bisa pakai fungsi rekursif jika ada nested reply)
                    renderComments(diskusiData, list);
                })
                .catch(err => {
                    console.error(err);
                    list.innerHTML = '<li>Gagal memuat diskusi.</li>';
                });
        }

        // Variabel DOM
        const form = document.getElementById('discussionForm');
        const list = document.getElementById('commentsList');

        // Fungsi rekursif render komentar (contoh sederhana)
        function renderComments(comments, parentElement) {
            comments.forEach(comment => {
                const li = document.createElement('li');
                li.style.padding = "8px";
                li.style.border = "1px solid #ddd";
                li.style.marginBottom = "8px";
                li.style.borderRadius = "4px";
                if(comment.ID_PARENT) {
                    li.style.marginLeft = "20px";
                    li.style.borderLeft = "3px solid #f5a201";
                    li.style.paddingLeft = "10px";
                }

                li.innerHTML = `<strong>${comment.ID_PEGAWAI??comment.ID_PEMBELI}</strong> (${comment.TANGGAL_POST}): <br>${comment.ISI_DISKUSI}`;
                parentElement.appendChild(li);

                // Jika ada reply (children), render rekursif
                if(comment.children && comment.children.length > 0){
                    const ulReply = document.createElement('ul');
                    ulReply.classList.add('list-unstyled');
                    li.appendChild(ulReply);
                    renderComments(comment.children, ulReply);
                }
            });
        }

        // 3. Submit komentar baru (baik komentar utama atau reply)
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const comment = document.getElementById('comment').value.trim();
            const idParent = document.getElementById('idParent').value || null;

            if (!comment) return alert('Komentar tidak boleh kosong');

            const postData = {
                KODE_PRODUK: kodeProduk,  // Pastikan pakai kodeProduk yang sama
                ISI_DISKUSI: comment,
                ID_PEGAWAI: 'P01',  // Contoh user pegawai (ubah sesuai login)
                ID_PEMBELI: null,   // Atau pembeli, sesuaikan
                ID_PARENT: idParent,
            };

            fetch('http://127.0.0.1:8000/api/diskusiProduk/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // 'X-CSRF-TOKEN': '{{ csrf_token() }}' // jika pake blade Laravel
                },
                body: JSON.stringify(postData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Komentar berhasil dikirim!');
                    form.reset();
                    document.getElementById('idParent').value = '';
                    // Reload diskusi terbaru dari server agar update otomatis
                    fetchDiskusi();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengirim komentar');
            });
        });

        // Load diskusi saat halaman siap
        fetchDiskusi();
    </script>

@include('outer.footer')
