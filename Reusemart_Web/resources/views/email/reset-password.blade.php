<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body>
    <p>Halo, {{ $user->NAMA_PEMBELI ?? $user->NAMA_PENITIP?? $user->NAMA_ORGANISASI  ?? 'Pengguna' }}</p>

    <p>Kami menerima permintaan untuk mereset password akun Anda.</p>

    <p>Klik link berikut untuk reset password Anda:</p>
    @if($url)
        <a href="{{ $url }}">{{ $url }}</a>
    @else
        <p>Debug URL: {{ $url ?? 'URL kosong' }}</p>
    @endif    

    <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>

    <p>Salam,<br>Tim Reusemart</p>
</body>
</html>
