@extends('layouts.backend')
@section('title','Permission')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Management</a></li>
                        <li class="breadcrumb-item active">Permission</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="card-title">Daftar Role</h4>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                @foreach ($roles as $role)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-normal mb-2">Total {{count($role->users)}} users</h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <h4 class="mb-1 text-primary">{{$role->nama}}</h4>
                                    <i class="fas fa-globe fa-3x text-primary"></i>
                                </div>
                                @can('can_update', [request()])
                                    <div class="d-flex justify-content-between">
                                        <a type='button' data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge"  onclick="show_detail('Role Permission', {{$role->id}})"><i class="fas fa-pen"></i> &nbsp; Edit Role Permission</a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div><!-- end row -->

@endsection
@section('scriptbawah')
    <script>
        //OPEN MODAL DETAIL DATA ROLE
        function show_detail(judul, id){
                $("#modalCenterExtraLargeLabel").html(judul)
                $("#modalCenterExtraLargeContent").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-border" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`);
                var act = "{{ route('permission.modal.detail', ['param' => ':param']) }}";
                    act = act.replace(':param', id);
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterExtraLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterExtraLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL DETAIL DATA ROLE
    </script>
@endsection
