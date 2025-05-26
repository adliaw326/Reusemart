<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Bekas Murah</title>
    <link rel="icon" type="image/png" href="{{ asset('icon/logo1.webp') }}">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: white; /* Set background to white */
            color: #0b1e33; /* Set text color to #0b1e33 */
        }
        .carousel-item img {
            width: 600px;
            height: 400px;
        }
        .card {
            background-color: #00537a;
            border: none;
            color: white;
        }
        .card-body {
            text-align: center;
        }
        /* Custom styling for left-aligned text */
        .text-left {
            text-align: left;
        }
        .logo-container {
            text-align: right;
        }
        .logo-container img {
            max-height: 150px;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <!-- New Section: Text Left Aligned and Logo on the Right -->
    <div class="container my-5">
        <div class="row">
            <!-- Left-Aligned Text -->
            <div class="col-md-8 text-left">
                <h2>Reusemart</h2>
                <p>E-commerce barang bekas telah menjadi solusi bagi banyak orang yang ingin membeli barang dengan harga terjangkau tanpa mengurangi kualitas. Dengan adanya platform online yang menyediakan berbagai produk bekas, konsumen dapat dengan mudah mencari dan mendapatkan barang-barang yang masih layak pakai, mulai dari elektronik, pakaian, hingga perabot rumah tangga. Keuntungan utama dari membeli barang bekas adalah harga yang jauh lebih murah dibandingkan dengan produk baru, namun tetap dapat memenuhi kebutuhan sehari-hari. Selain itu, e-commerce ini juga memberikan kemudahan bagi penjual untuk mendaur ulang barang-barang lama mereka yang masih berfungsi dengan baik. </p>
                <p>Selain aspek ekonomi, membeli barang bekas secara online juga memiliki dampak positif terhadap lingkungan. Dengan memperpanjang masa pakai barang-barang yang sudah ada, kita turut mengurangi jumlah sampah dan limbah yang dihasilkan. E-commerce barang bekas membantu meminimalisir produksi barang baru yang memerlukan sumber daya alam dan energi, sehingga mendukung konsep ekonomi sirkular yang ramah lingkungan. Platform ini juga memungkinkan konsumen untuk menemukan produk unik yang sulit didapatkan di pasar barang baru, memberikan pilihan yang lebih variatif dan menarik bagi setiap penggunanya.</p>
                <p>Selain itu, e-commerce barang bekas juga memfasilitasi kemudahan bagi konsumen untuk bertransaksi secara aman dan nyaman. Dengan sistem pembayaran online yang aman serta pengiriman yang efisien, pembeli dapat memperoleh barang bekas berkualitas tanpa harus khawatir mengenai proses transaksi. Banyak platform e-commerce juga menawarkan fitur ulasan dan rating dari pembeli sebelumnya, sehingga memberikan kepercayaan lebih bagi konsumen sebelum melakukan pembelian. Dengan begitu, e-commerce barang bekas bukan hanya solusi untuk mendapatkan produk berkualitas dengan harga terjangkau, tetapi juga sebuah pilihan cerdas untuk berbelanja secara bertanggung jawab dan ramah lingkungan.</p>
            </div>
            <!-- Right-Aligned Logo -->
            <div class="col-md-4 logo-container">
                <img src="{{ asset('icon/logoBesar.webp') }}" style="max-height: 700px;" alt="Logo Barang Bekas Murah">
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
