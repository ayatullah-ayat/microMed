
@extends('frontend.layouts.master')
<!-- Account Area Starts-->

@section('content')
    <section class="container-fluid account-area d-flex align-items-center" style="min-height: 50vh;">
    
        <div class="container">
    
            <div class="row">
    
                <div class="col-md-5 mx-auto">
    
                    <div class="account-form card">
    
                        <h3 class="text-center"> Login </h3>

                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif 


                        @if ($errors->any())
                        <div>
                            <div class="text-danger pl-2">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>
                        
                            <ul class="text-danger">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
    
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
    
                            <div class="form-group">
                                <input class="micro-form-control form-control border mt-3" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" Email Address ">
                            </div>
    
                            <div class="form-group">
                                <input type="password" class="micro-form-control form-control border mt-3"
                                id="password"
                                name="password"
                                required 
                                autocomplete="current-password"
                                placeholder=" Password ">
                            </div>
    
                            <div class="login-remember-content my-3">
    
                                <div class="checkbox">
                                    <input id="remember_me" class="magic-checkbox" type="checkbox" name="remember">
                                    <label for="remember_me" class="text-sm checkmark">
                                        Remember Me
                                    </label>
                                </div>
    
                                <div class="account-text">
                                    @if (Route::has('password.request'))
                                    <a class="mt-5 me-3 text-end text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                    @endif
                                </div>
    
                            </div>
    
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-sm btn-danger w-100 p-1"> Send </button>
                            </div>
    
                            <div class="form-group account-text">
                                <span> To Create New Account <a href="{{ route('register') }}" class="mt-5"> Register </a> Here
                                </span>
                            </div>
    
                        </form>
    
                    </div>
    
                </div>
    
            </div>
    
        </div>
    
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/login.css') }}">
@endpush
 
