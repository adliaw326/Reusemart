<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Produk - Barang Bekas Murah</title>
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            color: white;
            background-color: #fff;
        }
        /* Product Image Styles */
        .product-image {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        /* Product Details Container */
        .product-detail-container {
            margin-top: 20px;
            padding: 20px 0;
        }
        /* Product Info Styles */
        .product-card-title {
            font-size: 24px;
            font-weight: bold;
            color: #0b1e33;
        }
        .product-card-price {
            font-size: 18px;
            color: black;
            font-weight: 600;
        }
        .product-card-description {
            font-size: 16px;
            color: #333;
        }
        /* Bold Labels */
        .product-card-label {
            font-weight: bold;
            color: #0b1e33;
        }
        /* Custom Card Styles */
        .product-card-custom {
            background-color: #f0f0f0;
            border-radius: 15px;
            padding: 0;
        }
        .product-card-custom .card-body {
            background-color: #f0f0f0;
            padding: 15px 20px;
        }
        .product-card-custom .card-header {
            background-color: #e0e0e0;
            border-radius: 15px 15px 0 0;
            padding: 10px 20px;
            text-align: center;
        }
        /* Button Styles */
        .product-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .product-button {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            transition: opacity 0.3s ease;
            text-align: center;
            display: inline-block;
        }
        .product-button.add-to-cart {
            background-color: #4CAF50;
            color: white;
        }
        .product-button.buy-now {
            background-color: #f44336;
            color: white;
        }
        .product-button:hover {
            opacity: 0.8;
        }
        /* Other Products Section */
        .other-products-title {
            margin-top: 40px;
            font-size: 22px;
            font-weight: bold;
            color: #0b1e33;
            text-align: center;
        }
        .product-card img {
            object-fit: cover;
            width: 100%;
            height: 150px;
            border-radius: 15px 15px 0 0;
        }
        .other-products-section {
            margin-top: 30px;
        }
        .produk-rating {
            font-size: 16px;
            color: black;
            font-weight: bold;
            margin-top: 10px;
        }
        /* Star Rating */
        .star-rating i {
            color: #ffbc00;
            margin-right: 2px;
        }
        /* Penitip's Name Styles */
        .penitip-name {
            margin-top: 30px;
            font-size: 18px;
            font-weight: bold;
            color: #0b1e33;
        }
        /* Penitip's Rating Styles */
        .penitip-rating {
            font-size: 16px;
            color: black;
            font-weight: bold;
            margin-top: 10px;
        }
        .penitip-rating i {
            color: #ffbc00;
            margin-right: 2px;
        }
        li, ul {
            color: black;
            list-style-type: none;
            padding-left: 0;
        }
        ul.list-unstyled > li {
            padding: 8px;
            border: 1px solid #ddd;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        /* Nested comments style */
        ul.list-unstyled > li > div {
            margin-left: 20px;
            border-left: 3px solid #f5a201;
            padding-left: 10px;
        }
        /* Discussion Box */
        .discussion-box {
            background: white;
            color: black;
        }
    </style>
</head>
<body>
    @php
    /**
     * Recursive function to render comments and replies
     */
    function renderComments($comments) {
        echo '<ul class="list-unstyled">';
        foreach ($comments as $comment) {
            echo '<li>';
            if ($comment->ID_PARENT) {
                echo '<div>';
            }
            $authorName = $comment->pegawai->NAMA_PEGAWAI ?? $comment->pembeli->NAMA_PEMBELI ?? 'Anonim';
            echo "<strong>" . e($authorName) . "</strong> (" . e($comment->TANGGAL_POST) . "):<br>";
            echo nl2br(e($comment->ISI_DISKUSI));
            // Recursive rendering of replies
            if ($comment->children->count() > 0) {
                renderComments($comment->children);
            }
            if ($comment->ID_PARENT) {
                echo '</div>';
            }
            echo '</li>';
        }
        echo '</ul>';
    }
    @endphp

    <!-- Include Outer Header -->
    <div class="sticky-top">
        @include('outer.header')
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4" style="color: #0b1e33;">Detail Produk</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row product-detail-container">
            <!-- Product Images -->
            <div class="col-md-6 product-image-container">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://placehold.co/400x400?text=Foto+Thumbnail" class="d-block w-100 rounded" alt="{{ $produk->NAMA_PRODUK }} Foto Thumbnail" />
                        </div>
                        <div class="carousel-item">
                            <img src="https://placehold.co/400x400?text=Foto+2" class="d-block w-100 rounded" alt="{{ $produk->NAMA_PRODUK }} Foto 2" />
                        </div>
                    </div>
                    <div class="carousel-indicators mt-3">
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active rounded-circle bg-dark" style="width:12px; height:12px;" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1" class="rounded-circle bg-dark" style="width:12px; height:12px;" aria-label="Slide 2"></button>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6 product-info-container">
                <div class="card product-card-custom">
                    <div class="card-header">
                        <h5 class="product-card-title">{{ $produk->NAMA_PRODUK }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="product-card-price">
                            <span class="product-card-label">Harga: Rp </span>{{ number_format($produk->HARGA, 0, ',', '.') }}
                        </p>
                        <p class="product-card-description">
                            <span class="product-card-label">Deskripsi:</span> {{ $produk->KATEGORI }}<br>
                            <span class="product-card-label">Berat:</span> {{ $produk->BERAT }} Kg<br>
                            @php
                                use Carbon\Carbon;
                                $hariIni = Carbon::now()->startOfDay();
                                $garansiTanggal = $produk->GARANSI ? Carbon::parse($produk->GARANSI)->startOfDay() : null;
                            @endphp
                            <span class="product-card-label">Garansi:</span>
                            @if($garansiTanggal)
                                @if($garansiTanggal->gt($hariIni))
                                    Tersisa {{ $hariIni->diffInDays($garansiTanggal) }} hari
                                @else
                                    Garansi sudah habis
                                @endif
                            @else
                                Tidak ada
                            @endif
                            <br>
                            <span class="product-card-label">Kategori:</span> {{ $produk->kategori->NAMA_KATEGORI }}<br>
                        </p>

                        <p class="produk-rating">
                            <span class="product-card-label">Rating:</span> {{ number_format($produk->RATING ?? 0, 1) }}
                            <div class="star-rating">
                                @php
                                    $rating = $produk->RATING ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStars = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                @endphp
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @if($halfStars)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                @for($i = $fullStars + $halfStars; $i < 5; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                        </p>

                        <!-- Buttons -->
                        <div class="product-buttons">
                            <form id="hapusKeranjangForm" data-kode-produk="{{ $produk->KODE_PRODUK }}" style="display:none;">
                                @csrf
                                <button type="submit" class="btn product-button add-to-cart" style="background-color:#771111 ">Hapus dari Keranjang</button>
                            </form>
                            <form id="tambahKeranjangForm" data-kode-produk="{{ $produk->KODE_PRODUK }}" style="display:none;">
                                @csrf
                                <button type="submit" class="btn product-button add-to-cart">Masukkan Keranjang</button>
                            </form>
                            <button id="btnLoginDulu" class="btn product-button add-to-cart" disabled style="display:none;">Masukkan Keranjang (Login dulu)</button>
                        </div>

                        <!-- Penitip Info -->
                        <div>
                            <p class="penitip-name">
                                <span class="product-card-label">Nama Penitip: </span>
                                @if($produk->transaksiPenitipan->first() && $produk->transaksiPenitipan->first()->penitip)
                                    {{ $produk->transaksiPenitipan->first()->penitip->NAMA_PENITIP }}
                                @else
                                    Tidak Ditemukan
                                @endif
                            </p>

                            <p class="penitip-rating">
                                <span class="product-card-label">Rating rata-rata penitip: </span>
                                @if($produk->transaksiPenitipan->first() && $produk->transaksiPenitipan->first()->penitip && $produk->transaksiPenitipan->first()->penitip->RATING_RATA_RATA_P)
                                    @php
                                        $rating = $produk->transaksiPenitipan->first()->penitip->RATING_RATA_RATA_P;
                                        $fullStars = floor($rating);
                                        $halfStars = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                    @endphp
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @if($halfStars)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    @for($i = $fullStars + $halfStars; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                    {{ number_format($rating, 1) }}
                                @else
                                    0
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diskusi Produk -->
        <div class="col-md-12 mt-4">
            <div class="discussion-box p-3 rounded shadow-sm">
                <h4 class="mb-3" style="color: #0b1e33;">Forum Diskusi</h4>

                <form action="{{ route('diskusi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="KODE_PRODUK" value="{{ $produk->KODE_PRODUK }}" />
                    <input type="hidden" name="ID_PARENT" id="idParent" value="" />
                    <div class="mb-3">
                        <textarea name="ISI_DISKUSI" class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>

                <div class="mt-4">
                    @if($diskusi->count() > 0)
                        @php renderComments($diskusi); @endphp
                    @else
                        <p>Belum ada komentar untuk produk ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Produk Lainnya -->
        <h2 class="other-products-title">Produk Lainnya</h2>
        <div class="row g-3 other-products-section">
            @foreach($produk_lainnya as $item)
                <div class="col-6 col-sm-4 col-md-2 col-lg-2 mb-3">
                    <a href="/produk/{{ $item->KODE_PRODUK }}" class="card product-card-custom text-decoration-none">
                        <img src="https://placehold.co/200x200?text=Foto+Thumbnail" alt="{{ $item->NAMA_PRODUK }} Foto Thumbnail" />
                        <div class="card-body p-2">
                            <h5 class="product-card-title mb-1">{{ $item->NAMA_PRODUK }}</h5>
                            <p class="product-card-price mb-1">Rp {{ number_format($item->HARGA, 0, ',', '.') }}</p>
                            <p class="produk-rating mb-0">
                                {{ number_format($item->RATING ?? 0, 1) }}
                                <span class="star-rating">
                                    @php
                                        $rating = $item->RATING ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStars = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                    @endphp
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @if($halfStars)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    @for($i = $fullStars + $halfStars; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </span>
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const userId = localStorage.getItem('userId');
    const kodeProduk = document.querySelector('[data-kode-produk]')?.dataset.kodeProduk;

    if (!kodeProduk) {
        console.error('Kode produk tidak ditemukan.');
        return;
    }

    const hapusForm = document.getElementById('hapusKeranjangForm');
    const tambahForm = document.getElementById('tambahKeranjangForm');
    const btnLoginDulu = document.getElementById('btnLoginDulu');

    function showButtons(isInCart) {
        if (isInCart) {
            hapusForm.style.display = 'block';
            tambahForm.style.display = 'none';
            btnLoginDulu.style.display = 'none';
        } else {
            hapusForm.style.display = 'none';
            tambahForm.style.display = 'block';
            btnLoginDulu.style.display = 'none';
        }
    }

    if (!userId) {
        hapusForm.style.display = 'none';
        tambahForm.style.display = 'none';
        btnLoginDulu.style.display = 'block';
        return;
    }

    // Cek apakah produk sudah di keranjang (gunakan userId sebagai parameter)
    fetch(`/api/keranjang/check/${userId}/${kodeProduk}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal cek keranjang');
        return res.json();
    })
    .then(data => {
        showButtons(data.exists); // "exists" dari API
    })
    .catch(err => {
        console.error(err);
        hapusForm.style.display = 'none';
        tambahForm.style.display = 'none';
        btnLoginDulu.style.display = 'block';
    });

    // Hapus dari keranjang
    hapusForm.addEventListener('submit', function (e) {
        e.preventDefault();

        fetch(`/api/keranjang/delete/${userId}/${kodeProduk}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Produk berhasil dihapus dari keranjang.');
            showButtons(false);
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menghapus dari keranjang.');
        });
    });

    // Tambah ke keranjang
    tambahForm.addEventListener('submit', function (e) {
        e.preventDefault();

        fetch(`/api/keranjang/store/${userId}/${kodeProduk}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                ID_PEMBELI: userId,
                KODE_PRODUK: kodeProduk
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Produk berhasil ditambahkan ke keranjang.');
            showButtons(true);
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menambahkan ke keranjang.');
        });
    });
});

    </script>
</body>
</html>
