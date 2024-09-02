<div class="row p-4">
    <div class="col-12 col-lg-6">
        <p class="fw-bold mb-3">Depan</p>
        <div
            style="width:9cm; aspect-ratio:9/5.5; background-image:url('{{ asset('admin_assets/images/kartu/depan.png') }}'); background-size:9cm 5.5cm">
            <table>
                <tbody>
                    <tr>
                        <td style="width:3.95cm; height: 1.15cm"></td>
                        <td style="font-size: 7.5pt; vertical-align:bottom">{{ $profilAnggota->nomor_anggota }}</td>
                    </tr>
                </tbody>
            </table>
            <table style="border-collapse: collapse">
                <tbody>
                    <tr>
                        <td style="width: 3.6cm; height:0.6cm"></td>
                        <td style="font-size: 8.5pt; vertical-align:bottom">{{ $profilAnggota->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td style="width: 3.6cm;"></td>
                        <td style="font-size: 8.5pt; vertical-align:bottom; line-height:10pt">
                            {{ $profilAnggota->tempat }},
                            {{ \Carbon\Carbon::parse($profilAnggota->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 3.6cm;"></td>
                        <td style="font-size: 8.5pt; vertical-align:bottom; line-height:17pt">
                            {{ $profilAnggota->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <td style="width: 3.6cm;"></td>
                        <td style="font-size: 8.5pt; vertical-align:bottom; line-height:5pt">
                            {{ $profilAnggota->alamat }}</td>
                    </tr>
                    <tr>
                        <td style="width: 3.6cm;"></td>
                        <td style="font-size: 8.5pt; vertical-align:bottom; line-height:18pt">-</td>
                    </tr>
                </tbody>
            </table>
            <table style="transform: translateY(-8.7px)">
                <tbody>
                    <tr>
                        <td style="width:5.4cm"></td>
                        <td style="font-size: 6pt;">
                            {{ $profilAnggota->tanggal_verified? \Carbon\Carbon::parse($profilAnggota->tanggal_verified)->locale('id')->isoFormat('D MMMM Y'): '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            {{-- {{$profilAnggota->user->foto->file_path ??'00'}} --}}
            @if ($profilAnggota->user->foto)
                <img src="{{ asset('storage/' . $profilAnggota->user->foto->file_path) }}" alt=""
                style="width: 1.23cm; height:1.4cm; object-fit: cover; object-position: center; transform: translate(1.2cm,-.38cm)">
            @else
                <img src="{{ asset('storage/user/default-profil.png') }}" alt=""
                style="width: 1.23cm; height:1.4cm; object-fit: cover; object-position: center; transform: translate(1.2cm,-.38cm)">
            @endif
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <p class="fw-bold mb-3">Belakang</p>
        <img src="{{ asset('admin_assets/images/kartu/belakang.png') }}" alt="" style="max-width: 9cm">
    </div>
    <div class="col-12 d-flex justify-content-end mt-4">
        <a class="btn btn-primary"
            href="{{ route('cetakkartukeanggotaan.modal.pdf', $profilAnggota->id) }}">Download</a>
    </div>
</div>
