<style>
    .dropdown-toggle::after {
    display: none;
    margin-left: 0.255em;
    vertical-align: 0.255em;
    content: "";
    border-top: 0.3em solid;
    border-right: 0.3em solid transparent;
    border-bottom: 0;
    border-left: 0.3em solid transparent;
}

.btn-no-border{
    border: none;
    background: none;
}

ul.dropdown-menu {
    width: 200% !important;
    background: none;
    border: none;
    border-radius: 5px;
}

ul.dropdown-menu li{
    width: 200% !important;
    background: #fff;
    border: 1px solid #8b8b8b;
    padding: 10px 10px 10px 10px;
    margin-left: -100%;
}
</style>
<div class="shop-bx-title clearfix">
    <h5 class="text-uppercase">Riwayat Peminjaman</h5>
</div>

<div class="card-body">
    <div class="table-responsive">
        <table id="table_riwayat"  class="table-responsive-sm table check-tbl m-0 nowrap" style="width: 120%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Kode Buku</th>
                <th class="text-center">Buku</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Tanggal Peminjaman</th>
                <th class="text-center">Tanggal Batas Kembali</th>
                <th class="text-center">Tanggal Pengembalian</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
            <td class="text-black">
                {{$loop->iteration}}
            </td>
            <td class="text-black">
                {{$item->itemBuku->buku->kode_buku}}
            </td>
            <td>
                <img src="{{ !isset($item->itemBuku->buku->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$item->itemBuku->buku->cover->file_path) }}" alt="book" style="width: 40px;border-radius:5px">
            </td>
            <td>
                <span class="text-black">{{$item->itemBuku->buku->judul}}</span>
            </td>
            <td class="text-black">{{$item->tanggal_pengajuan_pinjaman}}</td>
            <td class="text-black">{{$item->tanggal_batas_pinjaman}}</td>
            <td class="text-black">{{$item->tanggal_pengembalian_pinjaman}}</td>
            <td class="text-black"><span class="badge rounded-pill {{ ($item->status == 'Sudah Kembali') ? 'bg-success' :
                ($item->status == 'Belum Kembali' || $item->status  == 'Lewat Batas Waktu Pengambilan'  ? 'bg-danger' :
                    ($item->status == 'Sedang Dipinjam' ? 'bg-warning' : 'bg-primary')
                )
            }}
                ">{{$item->status}}</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="dropdown-toggle"  style="border:none;background:none;" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu" style=" width: 200% !important;" aria-labelledby="btnGroupDrop1">
                        <li><a class="text-black" style="font-weight: 400;" href="{{route('katalog-buku.cetakbuktipeminjamanpdf',$item->kode_nota_peminjaman)}}">Unduh Bukti Peminjaman</a></li>
                        @if($item->tanggal_batas_pinjaman >= date('Y-m-d') && $item->status != 'Sudah Kembali')
                        <li><button type="button" style="font-weight: 400;" id="btn_perpanjangan" class="btn-no-border text-black"  data-id="{{$item->id}}" data-judul="{{ $item->itemBuku->buku->judul }}" data-maxtanggal="{{$item->tanggal_batas_pinjaman}}" data-ispersetujuan="{{$item->is_persetujuan_permohoman_perpanjangan === false ? 'false' : ''}}" >Ajukan Perpanjangan Peminjaman</button></li>
                        @endif
                    </ul>
                  </div>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#table_riwayat').DataTable({
            "scrollX": true,
            "language": {
                "emptyTable": "Tidak ada data tersedia pada tabel riwayat peminjaman"
            }
        });

        $('[data-toggle="popover"]').popover({
            html: true,
            sanitize: false,
            content: function () {
                return $("#popover-content").html();
            }
        });
    });

    $("#btn_perpanjangan").click(function() {
        var persetujuan = $(this).data('ispersetujuan');
        if(persetujuan === false){
            Swal.fire('Warning', 'Anda tidak bisa melakukan perpanjangan kembali, karena perpanjangan sebelumnya ditolak', 'warning');
        }else{
            $('#modalPerpanjanganPeminjamanBuku_rellarphp').modal('show');
            var judulBuku = $(this).data('judul');
            var tglMaxPinjaman = $(this).data('maxtanggal');
            var idPinjaman = $(this).data('id');
            $("#judul_buku_perpanjangan").val(judulBuku);
            $("#id_pinjaman").val(idPinjaman);

            var tanggalPerpanjangan = new Date(tglMaxPinjaman);
            var tgl = tanggalPerpanjangan.getDate();
            var bln = tanggalPerpanjangan.getMonth() + 1; // Perlu ditambah 1 karena indeks bulan dimulai dari 0
            var thn = tanggalPerpanjangan.getFullYear();
            var formattedTanggalPeminjaman = tgl + '/' + bln + '/' + thn;
            $("#tanggal_perpanjangan_peminjaman").val(formattedTanggalPeminjaman);


            var tanggalPengembalian = new Date(tglMaxPinjaman);
            tanggalPengembalian.setDate(tanggalPengembalian.getDate() + 7);
            // var formattedTanggalPengembalian = tanggalPengembalian.toISOString().split('T')[0];
            // $("#tanggal_pengembalian_setelah_perpanjangan").val(formattedTanggalPengembalian);
            var tanggal = tanggalPengembalian.getDate();
            var bulan = tanggalPengembalian.getMonth() + 1; // Perlu ditambah 1 karena indeks bulan dimulai dari 0
            var tahun = tanggalPengembalian.getFullYear();

            // Menggabungkan nilai tanggal, bulan, dan tahun dalam format "tanggal/bulan/tahun"
            var formattedTanggalPengembalian = tanggal + '/' + bulan + '/' + tahun;

            // Menetapkan nilai baru pada elemen dengan ID "tanggal_pengembalian_setelah_perpanjangan" menggunakan jQuery
            $("#tanggal_pengembalian_setelah_perpanjangan").val(formattedTanggalPengembalian);

        }

    });

    $("#btn_perpanjangan_yakin").click(function() {
        var idPinjaman =  $("#id_pinjaman").val();
        $("#modalPerpanjanganPeminjamanBuku_rellarphp").modal('hide');
        $.ajax({
            url : "{{route('katalog-buku.ajukan-perpanjangan')}}",
            method : 'POST',
            data: {
                peminjaman_id: idPinjaman,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                if(res.success) {
                    Swal.fire('Berhasil', 'Perpanjangan peminjaman buku berhasil, silahkan menunggu verifikasi dari petugas', 'success');
                }else if(res.lewat) {
                    Swal.fire('Warning', 'Perpanjangan peminjaman buku gagal, karena sudah melewati batas akhir peminjaman', 'warning');
                }else if(res.sudah) {
                    Swal.fire('Warning', 'Buku ini sudah anda ajukan untuk perpanjangan', 'warning');
                }else if(res.sudahkembali) {
                    Swal.fire('Warning', 'Buku tidak dapat diperpanjang, karena statusnya sudah kembali', 'warning');
                }


            },
            error: function(err) {
                Swal.fire('Error', 'Perpanjangan Peminjaman Buku Gagal', 'error');
            }
        })
    });

    // function ajukanPerpanjangan(id){
    //     Swal.fire({
    //         title: "Apakah anda yakin?",
    //         text: "Akan meminjam buku ini",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#3085d6",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Ya"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url : "{{route('katalog-buku.ajukan-perpanjangan')}}",
    //                 method : 'POST',
    //                 data: {
    //                     peminjaman_id: id,
    //                     _token: "{{ csrf_token() }}"
    //                 },
    //                 success: function(res) {

    //                 },
    //                 error: function(err) {
    //                     Swal.fire('Warning', 'Peminjaman Buku Gagal, tidak ada stok buku tersedia', 'error');
    //                 }
    //             })
    //         }
    //     });

    // }
</script>

