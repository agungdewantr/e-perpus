<style>
        .heart-icon {
            width: 30px;
            height: 30px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            outline: none;
        }

        .heart-icon::before {
            content: '\2661';
            font-size: 30px;
            color: #ccc;
            transition: color 0.3s ease;
        }

        .heart-icon.active::before {
            content: '\2764';
            color: red;
        }
</style>
<div class="shop-bx-title clearfix">
    <h5 class="text-uppercase">Favorit</h5>
</div>
<div class="card-body">
    <div class="table-responsive">
    <table id="table_favorit"  class="table-responsive-sm table check-tbl m-0 nowrap" style="width: 200%">
        <thead>
            <tr>
                <th style="width:5%" class=""></th>
                <th style="width:5%" class="text-center">Buku</th>
                <th style="width:35%" class="text-center ">Judul</th>
                <th style="width:25%" class="text-center ">Rincian Buku</th>
                <th style="width:5%" class="text-center">Stok</th>
                <th style="width:125%" class=""></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $favorit)
            <tr>
            <td >
                <button class="heart-icon active" onclick="addToFavoritOrKeranjang('favorit',{{$favorit->bukus->id}})"></button>
            </td>
            <td >
                <img src="{{ !isset($favorit->bukus->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$favorit->bukus->cover->file_path) }}" alt="book" style="width: 150px;border-radius:5px">
            </td>
            <td >
                <span class="text-black">{{$favorit->bukus->judul}}</span>
            </td>
            <td  class="text-black">
                <li>Penulis : {{$favorit->bukus->penulises[0]->nama}}</li>
                <li>Penerbit : {{$favorit->bukus->penerbit->namaPenerbit}}</li>
                <li>Tahun terbit : {{ \Carbon\Carbon::parse($favorit->bukus->tanggal_terbit)->format('Y')}}</li>
                @if($favorit->bukus->jenis == 'Buku')
                <li>Lokasi Rak : {{$favorit->bukus->rak->kode}}</li>
                @endif
            </td>
            <td  class="text-black">{{$stok = App\Models\Buku::get_stok($favorit->bukus->id)}}</td>
            <td>
                <div class="col-12 mt-3 d-flex no-wrap">
                    <div class="col-md-6 mb-1">
                        <button onclick="pinjamBuku({{$favorit->bukus->id}})" class="btn btn-biru btn-sm btnPinjam" data-bs-toggle="modal">
                            Pinjam
                        </button>
                    </div>
                    <div class="col-md-6 mb-1">
                        <button onclick=" addToFavoritOrKeranjang('keranjang',{{$favorit->bukus->id}}, {{$stok}})" class="btn btn-biru btn-sm" style="font-size: 14pt;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>

            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

</div>

<script>
    $(document).ready(function() {
        // Define datatables
        $('#table_favorit').DataTable({
            "scrollX": true,
            "language": {
                "emptyTable": "Tidak ada data tersedia pada tabel favorit"

            }
        });
    });

    // hapus buku dari favorit
    function addToFavoritOrKeranjang(param, idBuku, stok) {
        @if (auth()->check())
        var auth = @json(auth()->user());
        var profilAnggota = @json(auth()->user()->profilAnggota);
        if (profilAnggota) {
            if(param == 'favorit'){
                url = "{{route('favorit.store')}}";
                var heartIcon = document.querySelector('.heart-icon');
                heartIcon.classList.toggle('active');
            }else{
                url = "{{route('keranjang.store')}}";
            }
            if(stok != 0){
                $.ajax({
                url,
                method : 'POST',
                data: {
                    buku_id: idBuku,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    $("#jumlah"+res.data).text(res.jumlah);
                        $("#jumlah"+res.data+"2").text(res.jumlah);
                    if(res.type == 'create'){
                        Swal.fire('Berhasil', 'Buku berhasil ditambahkan ke ' + res.data, 'success');
                    } else {
                        Swal.fire('Berhasil', 'Buku berhasil dihapus dari '+ res.data, 'success');
                        var act = "{{ route('detailanggota.index', ['param' => 'favorit']) }}";
                        $('.tab_detail_anggota_rellarphp').removeClass('active');
                            $.ajax({
                                type: 'GET',
                                url: act,
                                success: function (data) {
                                    $("#jumlah"+res.data).text(res.jumlah);
                                    $("#jumlah"+res.data+"2").text(res.jumlah);
                                    $('.tab_detail_anggota_rellarphp[data-target="favorit"]').addClass('active');
                                    $("#content_detail_anggota_rellarphp").html(data);
                                }
                            })

                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }else{
                Swal.fire('Gagal','Gagal menambahkan ke keranjang karena stok kosong','warning');
            }
        }else{
            $('#modalDataBelumLengkap_rellarphp').modal('show');
        }
        @else
        $('#modalDataBelumDaftar_rellarphp').modal('show');
        @endif
    }
</script>
