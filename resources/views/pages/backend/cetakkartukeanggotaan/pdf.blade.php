<!DOCTYPE html>
<html lang="en">

<head>
    <title>Document</title>
    <link href="{{ public_path('admin_assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
</head>

<body>
    <div style="margin-bottom: 10px; width: 9cm; height: 5.5cm; position: relative">
        <img src="{{ public_path('admin_assets/images/kartu/depan.png') }}" alt=""
            style="position: absolute; width: 9cm; height: 5.5cm;">
        <p style="position: absolute; font-size: 7.5pt; transform: translate(3.95cm, .75cm)">
            {{ $profilAnggota->nomor_anggota }}</p>
        <p style="position: absolute; font-size: 8.5pt; transform: translate(3.6cm, 1.33cm)">
            {{ $profilAnggota->nama_lengkap }}</p>
        <p style="position: absolute; font-size: 8.5pt; transform: translate(3.6cm, 1.8cm)">
            {{ $profilAnggota->tempat }}, {{ $tgl_lahir }}</p>
        <p style="position: absolute; font-size: 8.5pt; transform: translate(3.6cm, 2.3cm)">
            {{ $profilAnggota->pekerjaan }}</p>
        <p style="position: absolute; font-size: 8.5pt; transform: translate(3.6cm, 2.76cm)">
            {{ $profilAnggota->alamat }}</p>
        <p style="position: absolute; font-size: 8.5pt; transform: translate(3.6cm, 3.15cm)">
            {{ $tgl_berlaku }}</p>
        <p style="position: absolute; font-size: 6pt; transform: translate(5.4cm, 3.55cm)">
            {{ $tgl_verified }}</p>
        @if(@$profilAnggota->user->foto->file_path)
        <img src="{{ public_path('storage/' . $profilAnggota->user->foto->file_path) }}"
            style="width: 1.23cm; height:1.4cm; object-fit: cover; object-position: center; transform: translate(1.2cm,3.7cm); position: absolute">
        @endif
    </div>
    <div>
        <img src="{{ public_path('admin_assets/images/kartu/belakang.png') }}" alt="" style="max-width: 9cm">
    </div>
</body>

</html>
