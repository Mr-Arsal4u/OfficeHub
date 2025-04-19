@extends('layouts.guest')
@section('content')
@section('title', 'Register')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
            <h2 class="heading-section">Register</h2>
        </div>
    </div>
    <div class="row justify-content-center">

        <div class="col-lg-12 col-lg-4">
            <div class="login-wrap p-0">
                <h3 class="mb-4 text-center">Create Account Now</h3>
                <form method="POST" action="{{ route('register') }}" class="signin-form">
                    @csrf

                    <div class="form-row justify-content-center">
                        <!-- 1. Name -->
                        <div class="form-group col-md-5">
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control form-control-sm" placeholder="Name" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                        @enderror
                        <!-- 2. Email -->
                        <div class="form-group col-md-5">
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control form-control-sm" placeholder="Email" required autofocus>
                        </div>
                    </div>

                    @error('email')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror

                    <div class="form-row justify-content-center">
                        <!-- 3. Password -->
                        <div class="form-group col-md-5 position-relative">
                            <input id="password-field" name="password" type="password"
                                class="form-control form-control-sm" placeholder="Password" required>
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"
                                style="position: absolute; top: 50%; right: 10px; cursor: pointer;"></span>
                        </div>

                        <!-- 4. Confirm Password -->
                        <div class="form-group col-md-5 position-relative">
                            <input id="confirm-password-field" type="password" name="password_confirmation"
                                class="form-control form-control-sm" placeholder="Confirm Password" required>
                            <span toggle="#confirm-password-field" class="fa fa-fw fa-eye field-icon toggle-password"
                                style="position: absolute; top: 50%; right: 10px; cursor: pointer;"></span>
                        </div>
                    </div>

                    <div class="form-group col-md-4 mx-auto">
                        <button type="submit" class="form-control btn btn-primary submit px-3 btn-sm">Sign Up</button>
                    </div>
                </form>

                <p class="w-100 text-center">&mdash; Already Have An Account?
                    <a href="{{ route('login') }}">Login Now</a>
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
        console.log('here');

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
