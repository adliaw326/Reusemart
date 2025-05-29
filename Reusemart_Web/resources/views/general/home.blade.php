<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Bekas Murah</title>
    <link rel="icon" type="image/png" href="{{ asset('icon/logo1.webp') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            color: white;
        }

        .carousel-inner img {
            width: 400px;
            height: 600px;
            object-fit: cover;
            margin: 0 auto;
        }

        .category-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            cursor: pointer;
        }

        .category-title {
            text-align: center;
            color: #0b1e33;
        }

        .category-item {
            display: flex;
            justify-content: center;
            align-items: center;
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

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sticky-top">
        @include('outer.header')
    </div>

    <div class="main-content">
        <!-- Carousel -->
    <div id="productCarousel" class="carousel slide my-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/iklan_carousel1.png') }}" class="d-block w-100" alt="Foto Carousell 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/iklan_carousel2.png') }}" class="d-block w-100" alt="Foto Carousell 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/iklan_carousel3.png') }}" class="d-block w-100" alt="Foto Carousell 3">
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
                @foreach($kategori as $item)
                <div class="col-6 col-sm-4 col-md-2 col-lg-2 mb-4 category-item">
                    <div class="text-center">
                        <img src="https://placehold.co/200x200?text=Foto+Thumbnail" class="category-image" data-category-id="{{ $item->ID_KATEGORI }}" alt="{{ $item->NAMA_KATEGORI }} Foto Thumbnail">

                        <p class="category-title">{{ $item->NAMA_KATEGORI }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Product Section -->
        <div class="container my-5">
            <h2 class="text-center mb-4" style="color: #0b1e33;">Produk</h2>
            <div class="row g-3" id="product-list">
                @foreach($produk as $item)
                <div class="col-6 col-sm-4 col-md-2 col-lg-2 mb-3 product-item" data-category-id="{{ $item->ID_KATEGORI }}">
                    <a href="/produk/{{ $item->KODE_PRODUK }}" class="product-card h-100">
                        <img src="https://placehold.co/200x200?text=Foto+Thumbnail" class="category-image" alt="{{ $item->NAMA_PRODUK }} Foto Thumbnail">

                        <div class="product-card-body">
                            <p class="product-card-title">{{ $item->NAMA_PRODUK }}</p>
                            <p class="product-card-price">Rp{{ number_format($item->HARGA, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const categoryImages = document.querySelectorAll('.category-image');
            const productItems = document.querySelectorAll('.product-item');

            categoryImages.forEach(function(categoryImage) {
                categoryImage.addEventListener('click', function() {
                    const categoryId = categoryImage.getAttribute('data-category-id');
                    productItems.forEach(function(productItem) {
                        productItem.style.display = 'none';
                    });
                    productItems.forEach(function(productItem) {
                        if (productItem.getAttribute('data-category-id') === categoryId) {
                            productItem.style.display = 'block';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
