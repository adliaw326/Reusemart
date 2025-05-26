<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Barang Bekas Murah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            color: white;
        }

        .product-image {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .product-detail-container {
            margin-top: 20px;
            padding: 20px;
        }

        .product-image-container {
            max-width: 50%; /* Image on the left */
        }

        .product-info-container {
            max-width: 50%; /* Information on the right */
            padding-left: 30px; /* Space between image and details */
        }

        .product-card-title {
            font-size: 24px;
            font-weight: bold;
            color: #0b1e33;
        }

        .product-card-price {
            font-size: 18px;
            color: #f5a201;
        }

        .product-card-description {
            font-size: 16px;
            color: #333;
        }

        .product-card-label {
            font-weight: bold;
        }

        /* Card Custom Styles */
        .product-card-custom {
            background-color: #f0f0f0; /* Gray background */
            border-radius: 15px; /* Rounded corners */
            padding: 20px;
        }

        .product-card-custom .card-body {
            background-color: #f0f0f0; /* Same gray background */
        }

        .product-card-custom .card-header {
            background-color: #e0e0e0; /* Lighter gray for header */
            border-radius: 15px 15px 0 0; /* Rounded corners for the header */
        }

        /* Button Styles */
        .product-buttons {
            display: flex;
            gap: 10px; /* Space between buttons */
        }

        .product-button {
            flex: 1; /* Ensure buttons have equal width */
            padding: 15px; /* Add padding to make buttons look proportional */
            font-size: 16px;
            font-weight: bold;
        }

        .product-button.add-to-cart {
            background-color: #4CAF50; /* Green color */
            color: white;
        }

        .product-button.buy-now {
            background-color: #f44336; /* Red color */
            color: white;
        }

        .product-button:hover {
            opacity: 0.8; /* Slight opacity on hover */
        }

        /* Other Products Section */
        .other-products-title {
            margin-top: 40px; /* Add top margin for more space above the title */
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

        /* Space between the "Produk Lainnya" section and the products */
        .other-products-section {
            margin-top: 30px; /* Add space above the products */
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

                    <!-- Carousel Indicators (bulat di bawah) -->
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
                        <p class="product-card-price">Harga: Rp{{ number_format($produk->HARGA, 0, ',', '.') }}</p>
                        <p class="product-card-description">
                            <span class="product-card-label">Deskripsi:</span> {{ $produk->KATEGORI }}<br>
                            <span class="product-card-label">Berat:</span> {{ $produk->BERAT }} Kg<br>
                            @php
                                use Carbon\Carbon;
                                $hariIni = Carbon::now()->startOfDay(); // Pastikan hanya tanggal, tanpa jam
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
                            <span class="product-card-label">Rating:</span> {{ $produk->RATING ?? 'Tidak ada' }}<br>
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
