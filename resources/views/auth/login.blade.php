@extends('layouts.auth')

@section('content')

<!-- Display success message -->
@if(session('message'))
<div class="alert alert-solid-success d-flex align-items-center" role="alert">
    <span class="alert-icon rounded">
        <i class="icon-base ti tabler-check-circle icon-md"></i>
    </span>
    {{ session('message') }}
</div>
@endif

<!-- Display error message -->
@if(session('error'))
<div class="alert alert-solid-danger d-flex align-items-center" role="alert">
    <span class="alert-icon rounded">
        <i class="icon-base ti tabler-alert-circle icon-md"></i>
    </span>
    {{ session('error') }}
</div>
@endif

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{url('/login')}}" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <span class="text-primary">
                                    <img width="32" height="22" src="{{asset('images/logo/logo.png')}}" alt="">
                                </span>
                            </span>
                            <span class="app-brand-text demo text-heading fw-bold">DMBP</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Welcome to DMBP! 👋</h4>
                    <p class="mb-6">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-4" action="{{url('/login')}}" method="POST">
                        @csrf
                        <div class="mb-6 form-control-validation">
                            <label for="email" class="form-label">Email or Username</label>
                            <input
                                type="text"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="Enter your email or username"
                                autofocus
                            />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password"
                                />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="my-8">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0 ms-2">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                                <a href="auth-forgot-password-basic.html">
                                    <p class="mb-0">Forgot Password?</p>
                                </a>
                            </div>
                        </div> --}}

                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>

                    {{-- <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="{{url('/register')}}" class="text-primary">
                            <span>Create an account</span>
                        </a>
                    </p> --}}
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
</div>

@endsection
