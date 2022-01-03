@extends('layouts.app')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="row align-items-center text-center">
                    <div class="col-md-12">
                        <div class="card-body">
                            <img src="{{ asset('image/must_logo.png') }}" alt="" width="200" class="img-fluid mb-4">
                            <h4 class="mb-3 f-w-400">{{ strtoupper(getAppName()) }}</h4>
                            <form action="{{ route('login') }}" method="post">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="floating-label" for="username">{{ __('Username') }}</label>
                                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="floating-label" for="Password">{{ __('Password') }}</label>
                                    <input type="password" class="form-control" name="password" required autocomplete="current-password">
                                </div>
                                <div class="custom-control custom-checkbox text-left mb-4 mt-2">
                                    <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">{{ __('Remember me') }}</label>
                                </div>
                                <button class="btn btn-block btn-primary mb-4">{{ __('Login') }}</button>
                            </form>
                            <p class="mb-2 text-muted">Forgot password? <a href="{{ route('password.request') }}" class="f-w-400">Reset</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
