<div class="shop-bx-title clearfix">
    <h5 class="text-uppercase">Keranjang</h5>
</div>

<div class="card-body">
    <div class="table-responsive">
        <table id="table_keranjang"  class="table-responsive-sm table nowrap check-tbl m-0" style="width: 120%">
        <thead>
            <tr>
                <th></th>
                <th class="text-center">Buku</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Rincian Buku</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data as $keranjang)
            <tr>
                <td>
                    <div class="form-check">
                    <input class="form-check-input" {{ App\Models\Buku::get_stok($keranjang->bukus->id) == 0 ? 'disabled' : ''}} type="checkbox" value="{{$keranjang->bukus->id}}" id="keranjang_{{$keranjang->bukus->id}}">
                </div>
                </td>
                <td>
                    <img src="{{ !isset($keranjang->bukus->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$keranjang->bukus->cover->file_path) }}" alt="book" style="width: 140px;border-radius:5px"><br>
                </td>
                <td>
                <span class="text-black">{{$keranjang->bukus->judul}}</span>
            </td>
                <td class="text-black">
                    <li>Penulis : {{$keranjang->bukus->penulises[0]->nama}}</li>
                    <li>Penerbit : {{$keranjang->bukus->penerbit->namaPenerbit}}</li>
                    <li>Tahun terbit : {{ \Carbon\Carbon::parse($keranjang->bukus->tanggal_terbit)->format('Y')}}</li>
                    @if($keranjang->bukus->jenis == 'Buku')
                        <li>Lokasi Rak : {{$keranjang->bukus->rak->kode}}</li>
                    @endif
                </td>
                <td class="text-black">{{$stok = App\Models\Buku::get_stok($keranjang->bukus->id)}}</td>
                <td><button type="button" onclick="deleteKeranjang({{$keranjang->bukus->id}})" class="btn btn-outline-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div class="row mt-5">
        <div class="col-8 d-flex justify-content-start">
             <span class="ms-4 text-black fw-bold">Total : {{$data->count()}} Buku</span>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <button class="btn btn-biru btn-sm btnPinjam mb-1" onclick="pinjamBukuKeranjang();">Pinjam</button>
        </div>
    </div>
    <div class="row">
        <small class="ms-3">*Satu anggota hanya dapat meminjam maksimal 3 buku dalam satu kali peminjaman</small>
    </div>
</div>
<script>
        $(document).ready(function() {
                // Define datatables
                $('#table_keranjang').dataTable( {
                    "scrollX": true,
                    "ordering": false,
                    "language": {
                        "emptyTable": "Tidak ada data tersedia pada tabel keranjang"
                    }
                } );
        });
        function deleteKeranjang(keranjangId){
            Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Apakah Anda ingin menghapus buku ini dari keranjang?',
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: 'Hapus',
                        cancelButtonText: `Batal`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{route('keranjang.store')}}",
                                method : 'POST',
                                data: {
                                    buku_id: keranjangId,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(res) {
                                    $("#jumlah"+res.data).text(res.jumlah);
                                    $("#jumlah"+res.data+"2").text(res.jumlah);
                                    if(res.type == 'create'){
                                        Swal.fire('Berhasil', 'Buku berhasil ditambahkan ke keranjang', 'success');
                                    } else {
                                        Swal.fire('Berhasil', 'Buku berhasil dihapus dari keranjang', 'success');
                                        var act = "{{ route('detailanggota.index', ['param' => 'keranjang']) }}";
                                        $('.tab_detail_anggota_rellarphp').removeClass('active');
                                        $.ajax({
                                            type: 'GET',
                                            url: act,
                                            success: function (data) {
                                                $("#jumlah"+res.data).text(res.jumlah);
                                                $("#jumlah"+res.data+"2").text(res.jumlah);
                                                $('.tab_detail_anggota_rellarphp[data-target="keranjang"]').addClass('active');
                                                $("#content_detail_anggota_rellarphp").html(data);
                                            }
                                        })
                                    }
                                },
                                error: function(err) {
                                    console.log(err);
                                }
                        })
                        }
                    })
        }

        function pinjamBukuKeranjang() {
            @if (auth()->check())
                var auth = @json(auth()->user());
                var profilAnggota = @json(auth()->user()->profilAnggota);
                if (!profilAnggota) {
                    $('#modalDataBelumLengkap_rellarphp').modal('show');
                }else{
                    if(profilAnggota.is_verified == true){
                        // Mengumpulkan ID buku yang dicentang
                        var selectedBooks = [];
                        $('input[type=checkbox]:checked').each(function() {
                            selectedBooks.push($(this).val());
                        });

                        // Periksa apakah ada buku yang dipilih
                        if (selectedBooks.length === 0) {
                            Swal.fire('Warning', 'Pilih setidaknya satu buku untuk dipinjam.', 'warning');
                            return;
                        }

                        if (selectedBooks.length > 3) {
                            Swal.fire('Warning', 'Maksimal hanya dapat meminjam 3 buku.', 'warning');
                            return;
                        }

                        // Kirim data menggunakan AJAX
                        $.ajax({
                            url : "{{route('katalog-buku.pinjam')}}",
                            method: 'POST',
                            data: {
                                buku_id : selectedBooks,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                if (res.responses.some(response => response.success)) {
                                    if (res.responses.length === 1) {
                                        var tanggalAmbil = res.responses[0].tanggal_ambil
                                        var judulBuku = res.responses[0].judul_buku
                                        var noAnggota = res.responses[0].nomor_anggota
                                        var namaAnggota = res.responses[0].nama_anggota
                                        $('#nomor_anggota').text(noAnggota);
                                        $('#nama_anggota').text(namaAnggota);
                                        $('#tanggal_diambil').text(tanggalAmbil);
                                        $('#list_buku_pinjam').text(judulBuku);
                                        $('#tgl_max_ambil').text(tanggalAmbil);
                                        $('#total_buku_pinjam').text(res.responses.length);
                                        $('#downloadPdfButton').attr('href', '/cetakbuktipeminjaman/' + res.responses[0].kode_nota);
                                        $('#modalDataBerhasil_rellarphp').modal('show');
                                    } else {
                                        var tanggalAmbil = res.responses[0].tanggal_ambil
                                        var noAnggota = res.responses[0].nomor_anggota
                                        var namaAnggota = res.responses[0].nama_anggota
                                        var tableContent = '';
                                        res.responses.forEach((response, index) => {
                                            tableContent += `${index + 1}. ${response.judul_buku} <br>`;
                                                });
                                        $('#nomor_anggota').text(noAnggota);
                                        $('#nama_anggota').text(namaAnggota);
                                        $('#tanggal_diambil').text(tanggalAmbil);
                                        $('#tgl_max_ambil').text(tanggalAmbil);
                                        $('#total_buku_pinjam').text(res.responses.length);
                                        $('#list_buku_pinjam').html(tableContent);
                                        $('#downloadPdfButton').attr('href', '/cetakbuktipeminjaman/' + res.responses[0].kode_nota);
                                        $('#modalDataBerhasil_rellarphp').modal('show');
                                    }
                                } else {
                                    if (res.responses.length === 1) {
                                        Swal.fire('Warning', 'Peminjaman Buku Gagal. Buku ini mungkin sudah Anda pinjam.', 'warning');
                                    } else {
                                        Swal.fire('Warning', 'Peminjaman Buku Gagal untuk beberapa buku. Beberapa buku mungkin sudah Anda pinjam.', 'warning');
                                    }
                                }
                                $("[data-target='keranjang']").addClass('active');
                                var act = "{{ route('detailanggota.index', ['param' => ':param']) }}";
                                        act = act.replace(':param', 'keranjang');
                                $.ajax({
                                    type: 'GET',
                                    url: act,
                                    success: function (data) {
                                        $("#content_detail_anggota_rellarphp").html(data);
                                    }
                                })

                            },
                            error: function(err) {
                                Swal.fire('Error', 'Peminjaman gagal. Silakan coba lagi.', 'error');
                                console.error('Error during AJAX request:', err);
                            }
                        });
                    } else{
                        Swal.fire('Warning', 'Akun anda belum terverifikasi.', 'warning');
                    }
                }
            @endif



        }
</script>


