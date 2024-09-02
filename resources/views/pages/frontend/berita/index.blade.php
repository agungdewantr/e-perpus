@extends('layouts.frontend')
@section('title', 'Berita')
@section('frontend.scriptatas')
    {{-- <style>
        .header-overview h5 {
            font-size: 15pt;
            padding-left: 1.2em;
            position: relative;
            bottom: 0;
            color: #000;
            /* margin-top: 30px; */
        }

        .header-overview h5:before {
            background: #8a8a8a;
            border-radius: 8px;
            bottom: 0;
            content: "";
            left: 0;
            position: absolute;
            top: -20px;
            width: 8px;
            height: 60px;
        }

        svg {
            width: 30px;
        }
        .dz-info{
            height: 250px;
        }

        .dz-info p{
            text-align: left;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style> --}}
    <style>
        .dz-title{
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .desc_berita{
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

    </style>
@endsection
@section('frontend.content')
<div class="page-content">
    <!-- inner page banner -->
    <div class="dz-bnr-inr overlay-primary-light dz-bnr-inr-sm" style="background-image: url('{{ asset('web_assets/images/about/grid_image.png') }}');">
        <div class="container">
            <div class="dz-bnr-inr-entry">
                <h1>Berita</h1>
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('beranda')}}"> Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{route('berita-perpustakaan')}}"> Berita</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- inner page banner End-->
    <!-- Blog Large -->
    <section class="content-inner-1 bg-img-fix">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form id="form">
                        <div class="input-group search-input">
                            <button class="btn" type="button" id="btn_filter"><i class="flaticon-loupe"></i></button>
                            <input type="text" id="judul" name="judul" class="form-control mb-2"
                                aria-label="Text input with dropdown button" placeholder="Cari Berita...">
                        </div>
                    </form>
                </div>
            </div>
            <div id="berita">

            </div>
        </div>
    </section>
</div>
@endsection

@section('frontend.scriptbawah')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            var act = "{{ route('berita-perpustakaan', ['param' => 'all']) }}";
            $.ajax({
                type: 'GET',
                url: act,
                success: function(data) {
                    $("#berita").html(data);
                }
            })

        $("#btn_filter").click(function() {
            filterBerita();
        });

        $("#judul").on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterBerita();
            }
        });
        function filterBerita() {
            let formData = new FormData(document.getElementById('form'));
            formData.append('_token', "{{ csrf_token() }}");

            $("#berita").html(`
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary m-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);

            $.ajax({
                url: "{{ route('filter.berita-perpustakaan') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $("#berita").html(data);
                },
                error: function(err) {
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            $("#" + key + "Error").text(value[0]);
                            $("#" + key).addClass('is-invalid');
                        });
                    }
                }
            });
        }

        $('body').on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    $("#berita").html(`
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary m-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);

                    var url = $(this).attr('href');
                    var method = '';
                    if(url.includes('all')){
                        method = 'GET';
                    }else{
                        method = 'POST';
                    }
                    // $.ajax({
                    //     type: 'GET',
                    //     url: url,
                    //     success: function (data) {
                    //         $("#berita").html(data);
                    //     }
                    // })
                    $.ajax({
                        url: url,
                        method: method,
                        data: {_token: "{{ csrf_token() }}"},
                        success: function (data) {
                            $("#berita").html(data);
                        }
                    })
                    window.history.pushState("", "", url);
                });

        });
    </script>
