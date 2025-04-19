@extends('layouts.guest')
@section('content')
@section('title', 'Login')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
            <h2 class="heading-section">Login</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="login-wrap p-0">
                <h3 class="mb-4 text-center">Have an account?</h3>
                <form method="POST" action="{{ route('login') }}" class="signin-form">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="form-control" placeholder="Email" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                        <input id="password-field" name="password" type="password" class="form-control"
                            placeholder="Password" required>
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                    </div>
                    <div class="form-group d-md-flex">
                        <div class="w-50">
                            <label class="checkbox-wrap checkbox-primary">Remember Me
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="w-50 text-md-right">
                            <a href="{{ route('password.email') }}" style="color: #fff">Forgot Password</a>
                        </div>
                    </div>
                </form>
                <p class="w-100 text-center">&mdash; Don't Have An Account?
                    <a href="{{ route('register') }}">Register Now</a>
                    &mdash;
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function(toggle) {

        toggle.addEventListener('click', function() {
            const input = document.querySelector(this.getAttribute('toggle'));
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endpush
