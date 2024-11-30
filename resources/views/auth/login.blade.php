@extends('admin.layouts.applog')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4>{{ __('Login') }}</h4>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Login') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-page')
<style>
    body {
        background-color: #f4f7fc;
        font-family: 'Nunito', sans-serif;
    }

    .card {
        border-radius: 15px;
    }

    .card-header {
        border-radius: 15px 15px 0 0;
    }

    .card-body {
        background: #ffffff;
    }

    .btn-primary {
        background-color: #46525f;
        border-color: #8298b1;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #262627;
        border-color: #f5f7fa;
    }

    .form-check-label {
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 10px;
    }

    .invalid-feedback {
        display: block;
    }

    .btn-link {
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    /* Optional: Add shadow to form inputs */
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(107, 166, 228, 0.5);
    }
</style>
@endpush
