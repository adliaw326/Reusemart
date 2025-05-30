<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Bekas Murah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            color: white;
            background-color: #0b1e33; /* Light background color for the page */
        }
        /* Wrapper styling */
        .container-wrapper {
            background-color: #013c58; /* Dark blue background for the wrapper */
            margin: 150px auto; /* Center the wrapper with space above and below */
            padding: 80px; /* Add padding inside the wrapper */
            max-width: 800px; /* Limit the max width of the wrapper */
            border-radius: 10px; /* Optional: Rounded corners */
        }
        /* Form title styling */
        .form-title {
            color: #ffba42; /* Title color */
            margin-bottom: 20px;
            text-align: center;
        }
        /* Form styles */
        .form-group label {
            color: #f5a201; /* Label color */
        }
        .btn-primary {
            background-color: #f5a201;
            border-color: #f5a201;
        }
        /* Carousel Images */
        .carousel-item img {
            width: 600px; /* Ensures the carousel images are responsive */
            height: 400px; /* Adjusts the height proportionally */
        }
    </style>

    <script>
        // Cek role dari localStorage
        const role = localStorage.getItem('role');

        if (role !== 'admin') {
            alert('Akses ditolak. Halaman ini hanya untuk pegawai.');
            window.location.href = '/login'; // Redirect ke halaman login atau dashboard sesuai user
        }
    </script>
</head>
<body>
    <!-- Include Header -->
    @include('outer.header')

    <!-- Wrapper with dark blue background, centered in the middle of the page -->
    <div class="container-wrapper">

        <div class="container">
            <h2 class="form-title">Tambah Data Pegawai</h2>

            <!-- Employee Data Form -->
            <form method="POST" action="{{ route('pegawai.store') }}">
                @csrf

                <!-- ID Pegawai -->
                <div class="form-group mb-3">
                    <label for="ID_PEGAWAI">ID Pegawai</label>
                    <input type="text" class="form-control" id="ID_PEGAWAI" name="ID_PEGAWAI" value="{{ $newId }}" readonly>
                </div>

                <!-- Nama Pegawai -->
                <div class="form-group mb-3">
                    <label for="NAMA_PEGAWAI">Nama Pegawai</label>
                    <input type="text" class="form-control" id="NAMA_PEGAWAI" name="NAMA_PEGAWAI" placeholder="Masukkan Nama Pegawai" required>
                </div>

                <!-- Email Pegawai -->
                <div class="form-group mb-3">
                    <label for="EMAIL_PEGAWAI">Email Pegawai</label>
                    <input type="email" class="form-control" id="EMAIL_PEGAWAI" name="EMAIL_PEGAWAI" placeholder="Masukkan Email Pegawai" required>
                </div>

                <!-- Password Pegawai -->
                <div class="form-group mb-3">
                    <label for="PASSWORD_PEGAWAI">Password Pegawai</label>
                    <input type="password" class="form-control" id="PASSWORD_PEGAWAI" name="PASSWORD_PEGAWAI" placeholder="Masukkan Password Pegawai" required>
                </div>

                <!-- Tanggal Lahir -->
                <!-- <div class="form-group mb-3">
                    <label for="TANGGAL_LAHIR">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="TANGGAL_LAHIR" name="TANGGAL_LAHIR" required>
                </div> -->

                <!-- Role Selection -->
                <div class="form-group mb-3">
                    <label for="ID_ROLE">Role</label>
                    <select class="form-control" id="rolePegawai" name="ID_ROLE" required>
                        <option value="" disabled selected>Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->ID_ROLE }}">{{ $role->NAMA_ROLE }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
