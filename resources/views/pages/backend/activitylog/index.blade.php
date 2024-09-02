@extends('layouts.backend')
@section('title','Activity Log')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Management</a></li>
                        <li class="breadcrumb-item active">Activity Log</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <div></div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Daftar Activity Log</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableActivityLog" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-10">Waktu</th>
                                <th  class="w-20">User</th>
                                <th  class="w-60">Aktifitas</th>
                                <th  class="w-10">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($lastActivitys as $activity)
                                <tr>
                                    <td>{{$activity->created_at->format('d F Y')}} - {{$activity->created_at->format('h:i:s')}} WITA</td>
                                    <td>{{$activity->causer->username ??'-'}}</td>
                                    <td>{{$activity->description ??'-'}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_detail('Detail Activity Log',{{($activity->id)}})">
                                            <i class='fa fa-eye'></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scriptbawah')
    <script>
        $(document).ready(function() {
            var table = $("#tableActivityLog").DataTable({
                ordering: false,
            });
        });
        //OPEN MODAL DETAIL DATA
            function show_detail(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('activitylog.modal.detail', ['param' => ':param']) }}";
                    act = act.replace(':param', param);
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL DETAIL DATA
    </script>
@endsection
