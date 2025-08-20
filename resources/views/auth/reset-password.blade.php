@extends('layouts.guest')
@section('content')
@section('title', 'Reset Password')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
            <h2 class="heading-section">Reset Password</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="login-wrap p-0">
                <h3 class="mb-4 text-center">Create New Password</h3>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4 text-center" style="color: #28a745;">
                        {{ session('status') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger mb-4 text-center" style="color: #dc3545;">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}" class="signin-form">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email', $email) }}" 
                            class="form-control" placeholder="Email Address" readonly required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror

                    <div class="form-group position-relative">
                        <input id="password-field" type="password" name="password" 
                            class="form-control" placeholder="New Password" required>
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror

                    <div class="form-group position-relative">
                        <input id="confirm-password-field" type="password" name="password_confirmation" 
                            class="form-control" placeholder="Confirm New Password" required>
                        <span toggle="#confirm-password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-primary submit px-3">
                            {{ __('Reset Password') }}
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
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
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
