<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Barang Bekas Murah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container my-5">
        <h2 class="text-center mb-4">Produk</h2>
        <div class="row g-3">
            @foreach($produk as $item)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                <div class="card">
                    <img src="https://placehold.co/200x200" class="card-img-top" alt="{{ $item->NAMA_PRODUK }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->NAMA_PRODUK }}</h5>
                        <p class="card-text">Rp{{ number_format($item->HARGA, 0, ',', '.') }}</p>
                        <p class="card-text">Kategori: {{ $item->kategori->NAMA_KATEGORI }}</p>
                        <a href="/produk/{{ $item->KODE_PRODUK }}" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
