@extends('layouts.app')

@section('title', 'Register')

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

        .register-container {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 3rem;
            margin: 0 auto;
        }

        .register-card {
            border: none;
            overflow: hidden;
            width: 100%;
        }

        .register-image {
            width: 100%;
            height: 100px;
            background-image: url('{{ asset('img/LOGOTUNASJAYA.png') }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            margin-bottom: 2rem;
        }

        .register-form {
            padding: 0 2rem;
        }

        .btn-register {
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

        .small {
            font-size: 1rem;
        }
    </style>

    <div class="outer-container">
        <div class="register-container">
            <div class="register-card">
                <div class="register-image"></div>
                <div class="register-form">
                    <h1 class="text-gray-900 text-center">Create an Account</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="Enter Full Name" value="{{ old('name') }}" required
                                autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Enter Email Address" value="{{ old('email') }}" required
                                autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password" required
                                autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password-confirm"
                                name="password_confirmation" placeholder="Confirm Password" required
                                autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-register btn-block">
                            Register
                        </button>
                    </form>
                    <hr>
                    <div class="text-center mt-3">
                        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection