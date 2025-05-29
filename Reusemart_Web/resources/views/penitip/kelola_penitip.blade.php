<!-- filepath: resources/views/kelola_penitip.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Penitip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #0b1e33;
            color: #fff;
        }
        .table thead th {
            background: #013c58;
            color: #ffba42;
        }
        .btn-add {
            background: #ffba42;
            color: #013c58;
            font-weight: bold;
        }
        .btn-add:hover {
            background: #f5a201;
            color: #fff;
        }
        .btn-warning, .btn-danger {
            font-size: 0.9rem;
        }
        .fa-star, .fa-star-o {
            font-size: 1rem;
        }
        .btn-back {
            background: #013c58;
            color: #ffba42;
            border: none;
            font-size: 1.1rem;
            padding: 6px 14px;
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn-back:hover {
            background: #ffba42;
            color: #013c58;
        }
        .search-box {
            max-width: 300px;
        }
        .search-box input {
            background: #013c58;
            color: #ffba42;
            border: 1px solid #ffba42;
        }
        .search-box input::placeholder {
            color: #ffba42;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <button class="btn-back" onclick="window.location.href='/admin/dashboard'">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <h2 class="mb-0">Kelola Penitip</h2>
            </div>
            <div class="d-flex align-items-center">
                <form class="search-box me-2" onsubmit="return false;">
                    <input type="text" id="searchPenitip" class="form-control" placeholder="Cari Penitip...">
                </form>
                <a href="/kelola_penitip/create_penitip" class="btn btn-add">
                    <i class="fa fa-plus"></i> Add Penitip
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-white">
                <thead>
                    <tr class="text-center">
                        <th>ID PENITIP</th>
                        <th>EMAIL PENITIP</th>
                        <th>NAMA PENITIP</th>
                        <th>NIK</th>
                        <th>Rating Rata-Rata Penitip</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody id="penitipTableBody">
                    <tr><td colspan="6" class="text-center">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
