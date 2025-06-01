<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .nota {
            width: 350px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 5px;
            font-size: 14px;
        }

        .nota h2 {
            text-align: left; /* Mengubah agar berada di kiri */
            margin-bottom: 10px;
        }

        .nota p {
            margin: 5px 0;
        }

        .nota table {
            width: 100%;
            margin-top: 10px;
        }

        .nota table td {
            padding: 4px;
        }

        .nota .bold {
            font-weight: bold;
        }

        .nota .divider {
            margin-top: 10px;
            margin-bottom: 10px;
            border-top: 1px solid #000;
        }

        .nota .right-align {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="nota">
        <h2>ReUse Mart</h2>
        <p class="left-align">Jl. Wani Lembur No. 505 Yogyakarta</p>
        <br> 
        
        <p>No Nota                 : {{ $nota['no_nota'] }}</p>
        <p>Tanggal penitipan       : {{ $nota['tanggal_penitipan'] }}</p>
        <p>Masa penitipan sampai   : {{ $nota['masa_penitipan_sampai'] }}</p>
        <br>

        <p class="bold">Penitip: {{ $nota['penitip'] }}</p>
        <p>{{ $nota['alamat_penitip'] }}</p>
        <p>Delivery: {{ $nota['delivery'] }}</p>
        <br>

        <table>
            <tr>
                <td>{{ $nota['produk'] }}</td>
                <td>{{ $nota['harga'] }}</td>
            </tr>
            <!-- Menampilkan Garansi hanya jika ada -->
            @if(!empty($nota['garansi']))
                <tr>
                    <td>Garansi ON {{ \Carbon\Carbon::parse($nota['garansi'])->format('F Y') }}</td> <!-- Menampilkan format Garansi -->
                </tr>
            @endif
            <tr>
                <td>Berat barang: {{ $nota['berat_barang'] }} kg</td>
            </tr>
        </table>
        <br>

        <p class="bold">Diterima dan QC oleh:</p>
        <br>
        <br>
        <br>
        <br>
        <p>{{ $nota['qc_by'] }}</p>
    </div>
</body>
</html>
