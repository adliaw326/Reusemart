<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Barang Bekas Murah</title>
    <link rel="icon" href="{{ asset('images/logo1.webp') }}" type="image/webp">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            color: white;
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
            padding: 20px;
        }

        /* Product Info Styles */
        .product-card-title {
            font-size: 24px;
            font-weight: bold;
            color: #0b1e33;
        }

        .product-card-price {
            font-size: 18px;
            color: black; /* Make the price text black */
        }

        .product-card-description {
            font-size: 16px;
            color: #333;
        }

        /* Bold Labels */
        .product-card-label {
            font-weight: bold;
        }

        /* Custom Card Styles */
        .product-card-custom {
            background-color: #f0f0f0;
            border-radius: 15px;
            padding: 20px;
        }

        .product-card-custom .card-body {
            background-color: #f0f0f0;
        }

        .product-card-custom .card-header {
            background-color: #e0e0e0;
            border-radius: 15px 15px 0 0;
        }

        /* Button Styles */
        .product-buttons {
            display: flex;
            gap: 10px;
        }

        .product-button {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
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
        }

        .other-products-section {
            margin-top: 30px;
        }

        .produk-rating {
            font-size: 16px;
            color: black; /* Change text color to black */
            font-weight: bold;
            margin-top: 10px;
        }

        /* Star Rating */
        .star-rating i {
            color: #ffbc00; /* Gold color for the stars */
        }

        /* Penitip's Name Styles */
        .penitip-name {
            margin-top: 30px; /* Add distance from the "Masukkan Keranjang" button */
            font-size: 18px;
            font-weight: bold;
            color: #0b1e33;
        }

        /* Penitip's Rating Styles - Set color to black for text, yellow for stars */
        .penitip-rating {
            font-size: 16px;
            color: black; /* Change text color to black */
            font-weight: bold;
            margin-top: 10px;
        }

        .penitip-rating i {
            color: #ffbc00; /* Gold color for the stars */
        }
    </style>
</head>
<body>

    <!-- Include Outer Header -->
    <div class="sticky-top">
        @include('outer.header')
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4" style="color: #0b1e33;">Detail Produk</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Product Detail Card -->
        <div class="row product-detail-container">
            <!-- Product Image Section (Left) -->
            <div class="col-md-6 product-image-container">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <!-- Carousel Images -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://placehold.co/400x400?text=Foto+Thumbnail" class="d-block w-100 rounded" alt="{{ $produk->NAMA_PRODUK }} Foto Thumbnail">
                        </div>
                        <div class="carousel-item">
                            <img src="https://placehold.co/400x400?text=Foto+2" class="d-block w-100 rounded" alt="{{ $produk->NAMA_PRODUK }} Foto 2">
                        </div>
                    </div>

                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators mt-3">
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active rounded-circle bg-dark" style="width: 12px; height: 12px;" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1" class="rounded-circle bg-dark" style="width: 12px; height: 12px;" aria-label="Slide 2"></button>
                    </div>
                </div>
            </div>

            <!-- Product Info Section (Right) -->
            <div class="col-md-6 product-info-container">
                <div class="card product-card-custom">
                    <div class="card-header text-center">
                        <h5 class="product-card-title">{{ $produk->NAMA_PRODUK }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="product-card-price"> </p>
                        <p class="product-card-description">
                            <span class="product-card-label">Harga: Rp </span> {{ number_format($produk->HARGA, 0, ',', '.') }}<br>
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
                            <span class="produk-rating">Rating:</span>
                            <span class="product-card-label">{{ number_format($produk->RATING, 1) }}</span>
                            <div class="star-rating">
                                @php
                                    $rating = $produk->RATING ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStars = $rating - $fullStars >= 0.5 ? 1 : 0;
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
                            </div><br>
                        </p>

                        <!-- Buttons -->
                        <div class="product-buttons">
                            <a href="/keranjang" class="btn product-button add-to-cart">
                                Masukkan Keranjang
                            </a>
                            <a href="/beli/{{ $produk->KODE_PRODUK }}" class="btn product-button buy-now">
                                Beli Langsung
                            </a>
                        </div>
                        
                        <!-- Display Penitip's Name and Rating Below the Product -->
                        <div>
                            <p class="penitip-name">
                                @if($produk->transaksiPenitipan->first() && $produk->transaksiPenitipan->first()->penitip)
                                    <span class="product-card-label">Nama Penitip: </span>{{ $produk->transaksiPenitipan->first()->penitip->NAMA_PENITIP }}
                                @else
                                    <span class="product-card-label">Nama Penitip: </span>Tidak Ditemukan
                                @endif
                            </p>

                            <!-- Rating Penitip -->
                            <p class="penitip-rating">
                                <span class="product-card-label">Rating rata-rata penitip: </span>
                                @if($produk->transaksiPenitipan->first() && $produk->transaksiPenitipan->first()->penitip && $produk->transaksiPenitipan->first()->penitip->RATING_RATA_RATA_P)
                                    @php
                                        $rating = $produk->transaksiPenitipan->first()->penitip->RATING_RATA_RATA_P;
                                        $fullStars = floor($rating); // Full stars
                                        $halfStars = ($rating - $fullStars) >= 0.5 ? 1 : 0; // Half star if more than 0.5
                                    @endphp
                                    <!-- Loop for full stars -->
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    
                                    <!-- Add half star if needed -->
                                    @if($halfStars)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    
                                    <!-- Loop for empty stars to complete 5 stars -->
                                    @for($i = $fullStars + $halfStars; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                    
                                    {{ number_format($rating, 1) }} <!-- Display the rating number -->
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

        <!-- Other Products Section -->
        <h2 class="other-products-title">Produk Lainnya</h2>
        <div class="row g-3 other-products-section">
            @foreach($produk_lainnya as $item)
                <div class="col-6 col-sm-4 col-md-2 col-lg-2 mb-3">
                    <a href="/produk/{{ $item->KODE_PRODUK }}" class="card product-card-custom">
                        <img src="https://placehold.co/200x200?text=Foto+Thumbnail" class="product-card-img-top" alt="{{ $item->NAMA_PRODUK }} Foto Thumbnail">
                        <div class="card-body">
                            <h5 class="product-card-title">{{ $item->NAMA_PRODUK }}</h5>
                            <p class="product-card-price">Rp{{ number_format($item->HARGA, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
