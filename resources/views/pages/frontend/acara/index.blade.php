@extends('layouts.frontend')
@section('title', 'Acara')
@section('frontend.scriptatas')
    <style>
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

        .deskripsiAcara{
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height:75px;
        }

        .title{
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height:65px;
        }

        .content-box:hover .btnhover3{
            color:#fff;
        }

        /* nav .flex.justify-between .relative.inline-flex:first-child,
                                            nav .flex.justify-between .relative.inline-flex:nth-child(2) {
                                                display: none;
                                            } */
    </style>
@endsection
@section('frontend.content')
<div class="page-content">
    <!-- inner page banner -->
    <div class="dz-bnr-inr overlay-primary-light dz-bnr-inr-sm" style="background-image: url('{{ asset('web_assets/images/about/grid_image.png') }}');">
        <div class="container">
            <div class="dz-bnr-inr-entry">
                <h1>Acara</h1>
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('beranda')}}"> Beranda</a></li>
                        <li class="breadcrumb-item">Acara</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- inner page banner End-->
    <!-- Services -->
    <section class="content-inner bg-white mt-5">
        <div class="container">
            <div class="row mb-3 mt-2">
                <div class="col-12">
                    <form id="form">
                        <div class="input-group search-input">
                            <button class="btn" id="btn_filter" type="button"><i class="flaticon-loupe"></i></button>
                            <input type="text" class="form-control mb-2" id="judul" name="judul"
                                aria-label="Text input with dropdown button" placeholder="Cari Acara...">
                        </div>
                    </form>
                </div>
            </div>
            <div id="acara">

            </div>
        </div>
    </section>
</div>
@endsection
@section('frontend.scriptbawah')
    <script>
        $(document).ready(function() {
                var act = "{{ route('acara-perpustakaan', ['param' => 'all']) }}";
                $.ajax({
                    type: 'GET',
                    url: act,
                    success: function (data) {
                        $("#acara").html(data);
                    }
                })

                $("#btn_filter").click(function() {
                    filterAcara();

               });

               $("#judul").on('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterAcara();
                    }
                });

               function filterAcara(){
                let formData = new FormData(document.getElementById('form'));
                formData.append('_token', "{{ csrf_token() }}");
                $("#acara").html(`
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border text-primary m-1" role="status">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                </div>
                                                            `);
                $.ajax({
                    url: "{{ route('filter.acara-perpustakaan') }}",
                    method: "POST",
                    data:formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $("#acara").html(data);
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

                    $("#acara").html(`
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border text-primary m-1" role="status">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                </div>
                                                            `);

                    var url = $(this).attr('href');
                    if(url.includes('all')){
                        method = 'GET';
                    }else{
                        method = 'POST';
                    }
                    $.ajax({
                        url: url,
                        method: method,
                        data: {_token: "{{ csrf_token() }}"},
                    success: function (data) {
                        $("#acara").html(data);
                    }
                })
                    window.history.pushState("", "", url);
                });



        });
    </script>
@endsection
