<!-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Transaksi Pembelian</h2>
    <table class="table">
        <tr>
            <th>ID Pembelian</th>
            <td>{{ $transaksi->ID_PEMBELIAN }}</td>
        </tr>
        <tr>
            <th>Status Transaksi</th>
            <td>{{ $transaksi->STATUS_TRANSAKSI }}</td>
        </tr>
        <tr>
            <th>Tanggal Pesan</th>
            <td>{{ $transaksi->TANGGAL_PESAN->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Status Rating</th>
            <td>{{ $transaksi->STATUS_RATING }}</td>
        </tr>
    </table>
    <a href="{{ route('transaksi_pembelian.history') }}" class="btn btn-secondary">Kembali ke History</a>
</div>
@endsection -->
