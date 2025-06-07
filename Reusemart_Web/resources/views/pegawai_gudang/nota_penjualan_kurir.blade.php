<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .nota {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 5px;
            font-size: 14px;
        }

        .nota h2, .nota p {
            margin: 0;
            padding: 2px 0;
        }

        .nota h2 {
            text-align: left;
            margin-bottom: 10px;
        }

        .nota .alamat {
            text-align: center;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .nota .divider {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .nota table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .nota table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .right-align {
            text-align: right;
        }

        .center-align {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="nota">
        <h4 style="margin-bottom: 0;">ReUse Mart</h4>
        <p style="margin-top: 0px; font-size: 13px;">Jl. Green Eco Park No. 456 Yogyakarta</p>

        <table style="margin-top: 5px; border-collapse: collapse;">
            <tr style="margin-top: -2;">
                <td style="width: 140px; padding: 0;">No Nota</td>
                <td style="padding: 0;">: {{ $nota['no_nota'] }}</td>
            </tr>
            <tr style="margin-top: -2;">
                <td style="padding: 0;">Tanggal pesan</td>
                <td style="padding: 0;">: {{ $nota['tanggal_pesan'] }}</td>
            </tr>
            <tr style="margin-top: -2;">
                <td style="padding: 0;">Lunas pada</td>
                <td style="padding: 0;">: {{ $nota['tanggal_lunas'] }}</td>
            </tr>
            @if($nota['tanggal_kirim'] === '-')
            <tr style="margin-top: -2;">
                <td style="padding: 0;">Tanggal Ambil</td>
                <td style="padding: 0;">: {{ $nota['tanggal_ambil'] }}</td>
            </tr>
            @else
            <tr style="margin-top: -2;">
                <td style="padding: 0;">Tanggal Kirim</td>
                <td style="padding: 0;">: {{ $nota['tanggal_kirim'] }}</td>
            </tr>
            @endif
        </table>
        <br>
        <p><Strong>Pembeli :</Strong> {{$nota['email_pembeli']}}  / {{ $nota['nama_pembeli'] }}</p>
        <p>{{ $nota['alamat_pembeli'] }}</p>
        <p>Delivery: {{ $nota['delivery'] }}</p>
        <br>
        <table>
            @foreach($nota['produk_list'] as $produk)
            <tr style="padding-top: -2;">
                <td style="padding: 0;">{{ $produk['nama_produk'] }}</td>
                <td class="right-align" style="padding: 0;">{{ number_format($produk['harga'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <br>
            @php
                $totalPertama = $nota['total_harga'];

                if ($nota['tanggal_kirim'] === '-') {
                    $totalKedua = $totalPertama;
                } else {
                    $totalKedua = $totalPertama + 100000;
                }

                $totalKetiga = $totalKedua - ($nota['poin_diskon'] * 100);

                if($nota['total_harga'] >= 1500000 || $nota['tanggal_kirim'] !== '-'){
                    $totalAkhir = ($totalKetiga - 100000) / 10000;
                } else {
                    $totalAkhir = $totalKetiga / 10000;
                }

                if ($totalKetiga > 500000) {
                    $totalAkhir = $totalAkhir * 1.2; // tambah 20%
                }
            @endphp

            <tr style="padding-top: -2;">
                <td style="padding: 0;">Total</td>
                <td class="right-align" style="padding: 0;">{{ number_format($totalPertama, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 0;">Ongkos Kirim</td>
                <td class="right-align" style="padding: 0;">
                    @if($nota['total_harga'] >= 1500000 || $nota['tanggal_kirim'] !== '-')
                        {{ number_format(100000, 0, ',', '.') }}
                    @else
                        0
                    @endif
                </td>
            </tr>
            <tr style="padding-top: -2;">
                <td style="padding: 0;">Total</td>
                <td class="right-align" style="padding: 0;">{{ number_format($totalKedua, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 0;">Potongan {{$nota['poin_diskon']}} Poin</td>
                <td class="right-align" style="padding: 0;">- {{number_format($nota['poin_diskon'] * 100, 0, ',', '.')}}</td>
            </tr>
            <tr style="padding-top: -2;">
                <td style="padding: 0;">Total</td>
                <td class="right-align" style="padding: 0;">{{ number_format($totalKetiga, 0, ',', '.') }}</td>
            </tr>
            <br>
            <tr colspan='2'>
                <td style="padding: 0;">Poin dari pesanan ini: {{$totalAkhir}}</td>
            </tr>
            <tr>
                <td style="padding: 0;">Total Poin Customer: {{$nota['poin_pembeli']}}</td>
            </tr>
            <br>
            @php
                $pegawaiQc = null;

                foreach ($nota['produk_list'] as $produk) {
                    if (!empty($produk['id_pegawai_qc'])) {
                        $pegawaiQc = [
                            'id' => $produk['id_pegawai_qc'],
                            'nama' => $produk['nama_pegawai_qc']
                        ];
                        break; // berhenti di data pertama yang punya QC
                    }
                }
            @endphp

            @if($pegawaiQc)
                <p>QC oleh: {{ $pegawaiQc['nama'] }} - (P{{ $pegawaiQc['id'] }})</p>
            @else
                <p>Tidak ada data pegawai QC.</p>
            @endif
            <br>
            <tr>
                <td style="padding-left: 20px">Diterima oleh:</td>
            </tr>
            <br><br><br>
            <tr>
                <td>(............................)</td>
            </tr>
            <tr>
                <td style="padding: 0">Tanggal:.................</td>
            </tr>
        </table>
    </div>
</body>
</html>
