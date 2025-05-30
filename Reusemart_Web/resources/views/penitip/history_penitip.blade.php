@include('outer.header')

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Riwayat Produk Penitipan</title>
</head>

<body>
    <div class="container mt-5">
        <h3 class="mb-4">Riwayat Produk Penitipan {{ $penitip->NAMA_PENITIP ?? '' }}</h3>

        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Berat (kg)</th>
                    <th>Harga (Rp)</th>
                    <th>Garansi</th>
                    <th>Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produk as $item)
                <tr>
                    <td>{{ $item->KODE_PRODUK }}</td>
                    <td>{{ $item->NAMA_PRODUK }}</td>
                    <td>{{ $item->KATEGORI }}</td>
                    <td>{{ $item->BERAT }}</td>
                    <td>{{ number_format($item->HARGA, 0, ',', '.') }}</td>
                    <td>{{ $item->GARANSI ?? '-' }}</td>
                    <td>{{ $item->RATING ?? '-' }}</td>
                    <td>{{ $item->STATUS ?? 'Tidak diketahui' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada produk penitipan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
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

@include('outer.footer')
