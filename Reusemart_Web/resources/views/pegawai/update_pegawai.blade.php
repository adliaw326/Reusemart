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
            <h2 class="form-title">Ubah Data Pegawai</h2>

            <!-- Employee Data Form -->
            <form action="{{ url('pegawai/update/' . $pegawai->ID_PEGAWAI) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Pegawai -->
                <div class="form-group mb-3">
                    <label for="namaPegawai">Nama Pegawai</label>
                    <input type="text" class="form-control" id="namaPegawai" name="NAMA_PEGAWAI"
                        value="{{ old('NAMA_PEGAWAI', $pegawai->NAMA_PEGAWAI) }}" required>
                </div>

                <!-- Email Pegawai -->
                <div class="form-group mb-3">
                    <label for="emailPegawai">Email Pegawai</label>
                    <input type="email" class="form-control" id="emailPegawai" name="EMAIL_PEGAWAI"
                        value="{{ old('EMAIL_PEGAWAI', $pegawai->EMAIL_PEGAWAI) }}" required>
                </div>

                <!-- Password Pegawai -->
                <div class="form-group mb-3">
                    <label for="passwordPegawai">Password Pegawai</label>
                    <input type="password" class="form-control" id="passwordPegawai" name="PASSWORD_PEGAWAI"
                        placeholder="Masukkan Password Baru (Opsional)">
                </div>

                <!-- Role Selection -->
                <div class="form-group mb-3">
                    <label for="rolePegawai">Role</label>
                    <select class="form-control" id="rolePegawai" name="ID_ROLE" required>
                        <option value="" disabled>-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->ID_ROLE }}"
                                {{ $pegawai->ID_ROLE == $role->ID_ROLE ? 'selected' : '' }}>
                                {{ $role->NAMA_ROLE }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include Footer -->
    @include('outer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
