@extends('layouts.guest')
@section('content')
@section('title', 'Forgot Password')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
            <h2 class="heading-section">Forgot Password</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="login-wrap p-0">
                <h3 class="mb-4 text-center">Reset Your Password</h3>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4 text-center" style="color: #28a745;">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}" class="signin-form">
                    @csrf
                    
                    <div class="mb-4 text-center" style="color: #fff; font-size: 14px;">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="form-control" placeholder="Email Address" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-primary submit px-3">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>

                    <div class="form-group d-md-flex">
                        <div class="w-50">
                            <a href="{{ route('login') }}" style="color: #fff">Back to Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Add any additional JavaScript for forgot password functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle (if needed)
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const input = document.querySelector(this.getAttribute('toggle'));
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    });
</script>
@endpush
