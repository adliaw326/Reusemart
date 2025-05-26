<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Bekas Murah</title>
    <link rel="icon" type="image/png" href="{{ asset('icon/logo1.webp') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            color: white;
        }
        .carousel-item img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .card {
            background-color: #00537a;
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .card-body {
            text-align: center;
        }
        .category-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .category-title {
            text-align: center;
            color: #0b1e33;
        }
        .category-section {
            margin-top: 40px;
        }
        .category-item:hover .category-image {
            filter: brightness(85%);
            transform: scale(1.05);
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            color: #000;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-card-body {
            padding: 10px;
            text-align: center;
            flex: 1 1 auto;
        }
        .product-card-title {
            font-size: 16px;
            font-weight: bold;
            color: #0b1e33;
        }
        .product-card-price {
            font-size: 14px;
            color: #f5a201;
        }
        .product-card:hover {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .category-image, .product-card img {
                height: 150px;
            }
        }
        @media (max-width: 767.98px) {
            .category-image, .product-card img {
                height: 120px;
            }
            .carousel-item img {
                height: 200px;
            }
        }
        @media (max-width: 575.98px) {
            .category-section .row > div,
            .container.my-5 .row > div {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .category-image, .product-card img {
                height: 90px;
            }
            .carousel-item img {
                height: 120px;
            }
        }
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1 0 auto;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <div class="sticky-top">
        @include('outer.header')
    </div>
    <div class="main-content">
        <!-- Carousel -->
        <div id="productCarousel" class="carousel slide my-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/iklan_carousel.png') }}" class="d-block w-100" alt="Produk Elektronik Bekas">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/iklan_carousel2.png') }}" class="d-block w-100" alt="Perabot Rumah Bekas">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/iklan_carousel3.png') }}" class="d-block w-100" alt="Pakaian Bekas">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Category Section -->
        <div class="container category-section">
            <h2 class="text-center mb-4" style="color: #0b1e33;">Kategori</h2>
            <div class="row g-3">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Elektronik.webp') }}" class="category-image" alt="Elektronik">
                        <p class="category-title">Elektronik</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/KomputerAksesoris.webp') }}" class="category-image" alt="Komputer & Aksesoris">
                        <p class="category-title">Komputer & Aksesoris</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/HandphoneAksesoris.webp') }}" class="category-image" alt="Handphone & Aksesoris">
                        <p class="category-title">Handphone & Aksesoris</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Pakaian.webp') }}" class="category-image" alt="Pakaian">
                        <p class="category-title">Pakaian</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Perabotan Rumah.webp') }}" class="category-image" alt="Perabotan Rumah">
                        <p class="category-title">Perabotan Rumah</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Sepatu.webp') }}" class="category-image" alt="Sepatu">
                        <p class="category-title">Sepatu</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Tas.webp') }}" class="category-image" alt="Tas">
                        <p class="category-title">Tas</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Aksesoris Fashion.webp') }}" class="category-image" alt="Aksesoris Fashion">
                        <p class="category-title">Aksesoris Fashion</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Jam Tangan.webp') }}" class="category-image" alt="Jam Tangan">
                        <p class="category-title">Jam Tangan</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/AlatMusik.webp') }}" class="category-image" alt="Alat Musik">
                        <p class="category-title">Alat Musik</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/KebutuhanBayi.webp') }}" class="category-image" alt="Kebutuhan Bayi">
                        <p class="category-title">Kebutuhan Bayi</p>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="{{ asset('images/Otomotif.webp') }}" class="category-image" alt="Otomotif">
                        <p class="category-title">Otomotif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Section -->
        <div class="container my-5">
            <h2 class="text-center mb-4" style="color: #0b1e33;">Produk Terbaru</h2>
            <div class="row g-3">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                    <div class="product-card h-100">
                        <img src="{{ asset('images/Elektronik.webp') }}" class="category-image" alt="Elektronik">
                        <div class="product-card-body">
                            <p class="product-card-title">Monitor Samsung 27"</p>
                            <p class="product-card-price">Rp4.269.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                    <div class="product-card h-100">
                        <img src="{{ asset('images/KomputerAksesoris.webp') }}" class="category-image" alt="Komputer & Aksesoris">
                        <div class="product-card-body">
                            <p class="product-card-title">Laptop HP 14"</p>
                            <p class="product-card-price">Rp6.320.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                    <div class="product-card h-100">
                        <img src="{{ asset('images/HandphoneAksesoris.webp') }}" class="category-image" alt="Handphone & Aksesoris">
                        <div class="product-card-body">
                            <p class="product-card-title">Smartphone Xiaomi</p>
                            <p class="product-card-price">Rp2.199.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                    <div class="product-card h-100">
                        <img src="{{ asset('images/Pakaian.webp') }}" class="category-image" alt="Pakaian">
                        <div class="product-card-body">
                            <p class="product-card-title">Kaos Pria</p>
                            <p class="product-card-price">Rp150.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                    <div class="product-card h-100">
                        <img src="{{ asset('images/Perabotan Rumah.webp') }}" class="category-image" alt="Perabotan Rumah">
                        <div class="product-card-body">
                            <p class="product-card-title">Meja Makan Kayu</p>
                            <p class="product-card-price">Rp1.200.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                    <div class="product-card h-100">
                        <img src="{{ asset('images/Sepatu.webp') }}" class="category-image" alt="Sepatu">
                        <div class="product-card-body">
                            <p class="product-card-title">Sepatu Adidas</p>
                            <p class="product-card-price">Rp850.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Include Footer -->
    @include('outer.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
