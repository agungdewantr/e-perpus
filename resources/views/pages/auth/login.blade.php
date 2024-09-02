<div class="row mt-4">
    <h4 class="text-center text-black mb-2">Masuk</h4>
    <form class="" action="{{ route('proses.login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control text-black {{ isset($input['error']) ? 'is-invalid' : '' }}"
                id="username" name="username" placeholder="Masukkan Username" required>
            <div class="invalid-feedback">
                {{ $input['error'] ?? '' }}
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                </div>
                <div class="flex-shrink-0">
                    <div class="">
                        {{-- <a href="auth-recoverpw.html" class="text-muted">Lupa password?</a> --}}
                    </div>
                </div>
            </div>

            {{-- <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control text-black" placeholder="Masukkan password" aria-label="Password" aria-describedby="password-addon" name="password" required>
                <button class="btn btn-light shadow-none ms-0" type="button" id="password-show"><i class="fa fa-eye"></i></button>
            </div> --}}
            <div class="input-group">
                <input class="form-control text-black" name="password" type="password" value=""
                    placeholder="Masukkan Password" id="password" required>
                <button type="button" id="togglePassword" class="btn btn-outline-light text-black"
                    style="border: 1px solid #d6d6d6;">
                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>
                <div class="validation-password text-danger">
                    <span class="text-muted" style="font-size: 11px">
                        Minimal 8 Karakter kombinasi huruf kapital, huruf kecil, angka dan simbol(@$!#%*?&).
                    </span>
                </div>
            </div>
        </div>
        {{-- <div class="row mb-4">
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-check">
                    <label class="form-check-label" for="remember-check">
                        Remember me
                    </label>
                </div>
            </div>

        </div> --}}
        <div class="mb-3">
            <button class="btn btn-primary w-100" type="submit">{{ __('Login') }}</button>
        </div>
    </form>
</div>
<div class="row">
    <div class="text-center">
        <p class="text-muted mb-0">Belum memiliki akun ?
            <button class="btn btn-link text-primary fw-semibold" data-bs-target="#modalLogin"
                onclick="show_register()"> Klik Daftar </button>
        </p>
    </div>
</div>
<script src="{{ asset('/') }}web_assets/js/jquery.min.js"></script><!-- JQUERY MIN JS -->
<script type="text/javascript">
    $(document).ready(function() {
        //OPEN PASSWORD LIHAT
        var passwordInput = $('#password');
        var togglePasswordButton = $('#togglePassword');

        togglePasswordButton.click(function() {
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                togglePasswordButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
            } else {
                passwordInput.attr('type', 'password');
                togglePasswordButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }
        });
        //CLOSE PASSWORD LIHAT
    });
</script>
