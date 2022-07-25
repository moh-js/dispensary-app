@extends('layouts.app')

@section('content')

    @if (session('status'))
        <div>
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <div class="auth-wrapper">
        <!-- [ reset-password ] start -->
        <div class="auth-content">
            <div class="card">
                <div class="row align-items-center text-center">
                    <div class="col-md-12">
                        <div class="card-body">
                            <img src="{{ asset('image/must_logo.png') }}" alt="" width="200" class="img-fluid mb-4">
                            <h4 class="mb-3 f-w-400">Reset your password</h4>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="floating-label" for="Username">{{ __('Email') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus />

                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary mb-2">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                                <p class="mb-0 text-muted">Remember your password account? <a href="{{ url('/login') }}" class="f-w-400">Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ reset-password ] end -->
    </div>

@endsection
