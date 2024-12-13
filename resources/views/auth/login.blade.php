@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .main-content {
            padding: 0px;
        }

        .outer-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 3rem;
            margin: 0 auto;
        }

        .login-card {
            border: none;
            overflow: hidden;
            width: 100%;
        }

        .login-image {
            width: 100%;
            height: 100px;
            background-image: url('{{ asset('img/LOGOTUNASJAYA.png') }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            margin-bottom: 2rem;
        }

        .login-form {
            padding: 0 2rem;
        }

        .btn-login {
            font-size: 1.1rem;
            letter-spacing: 0.05rem;
            padding: 0.9rem 1.2rem;
            width: 100%;
        }

        .form-control {
            font-size: 1.1rem;
            padding: 0.9rem 1.2rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2.4rem;
            margin-bottom: 2rem;
        }

        .custom-control-label {
            font-size: 1.1rem;
        }

        .small {
            font-size: 1rem;
        }
    </style>

    <div class="outer-container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-image"></div>
                <div class="login-form">
                    <h1 class="text-gray-900 text-center">Welcome Back!</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Enter Email Address..." value="{{ old('email') }}" required
                                autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password" required
                                autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="remember">Remember Me</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-login btn-block">
                            Login
                        </button>
                    </form>
                    <hr>
                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                    @endif
                    {{-- <div class="text-center mt-3">
                        <a class="small" href="{{ route('register') }}">Create an Account!</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
