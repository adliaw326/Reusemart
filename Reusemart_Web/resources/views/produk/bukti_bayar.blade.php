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

        <p><strong>Transaksi ID:</strong> {{ $transaksi->ID_PEMBELIAN }}</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('transaksi_pembelian.uploadBuktiBayar', $transaksi->ID_PEMBELIAN) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="bukti_bayar" class="form-label">Pilih File Bukti Bayar (gambar):</label>
                <input type="file" class="form-control" id="bukti_bayar" name="BUKTI_BAYAR" accept="image/*" required>
                @error('BUKTI_BAYAR')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Upload Bukti Bayar</button>
        </form>

        @if($transaksi->BUKTI_BAYAR)
            <h5 class="mt-4">Bukti Bayar yang sudah diupload:</h5>
            <img src="{{ asset('storage/' . $transaksi->BUKTI_BAYAR) }}" alt="Bukti Bayar" class="bukti-img">
        @endif
    </div>

</body>
</html>
@include('outer.footer')
