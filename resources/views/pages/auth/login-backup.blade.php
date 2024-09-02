@extends('layouts.authapp')
@section('title','Login')
@section('content')
    <div class="text-center">
        <h5 class="mb-0">Selamat Datang Kembali !</h5>
        <p class="text-muted mt-2">Login E-Perpustakaan, Kab. Tolitoli.</p>
    </div>
    <form class="mt-4 pt-2" action="{{ route('proses.login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email">
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <label class="form-label">Password</label>
                </div>
                <div class="flex-shrink-0">
                    <div class="">
                        {{-- <a href="auth-recoverpw.html" class="text-muted">Lupa password?</a> --}}
                    </div>
                </div>
            </div>

            <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control" placeholder="Masukkan password" aria-label="Password" aria-describedby="password-addon" name="password">
                <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-check">
                    <label class="form-check-label" for="remember-check">
                        Remember me
                    </label>
                </div>
            </div>

        </div>
        <div class="mb-3">
            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">{{ __('Login') }}</button>
        </div>
    </form>
    {{--<div class="mt-4 pt-2 text-center">
        <div class="signin-other-title">
            <h5 class="font-size-14 mb-3 text-muted fw-medium">- Sign in with -</h5>
        </div>

        <ul class="list-inline mb-0">
            <li class="list-inline-item">
                <a href="javascript:void()"
                    class="social-list-item bg-primary text-white border-primary">
                    <i class="mdi mdi-facebook"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="javascript:void()"
                    class="social-list-item bg-info text-white border-info">
                    <i class="mdi mdi-twitter"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="javascript:void()"
                    class="social-list-item bg-danger text-white border-danger">
                    <i class="mdi mdi-google"></i>
                </a>
            </li>
        </ul>
    </div> --}}

    <div class="mt-5 text-center">
        <p class="text-muted mb-0">Tidak mempunyai akun ? <a href="{{route('register')}}"
                class="text-primary fw-semibold"> Daftar sekarang </a> </p>
    </div>
<!-- end col -->
@endsection

