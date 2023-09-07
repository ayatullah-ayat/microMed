
@extends('frontend.layouts.master')
<!-- Account Area Starts-->

@section('content')
<section class="container-fluid account-area">

    <div class="container">

        <div class="row">

            <div class="col-md-5 mx-auto">

                <div class="account-form card">

                    <h3 class="text-center"> Register </h3>

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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <input 
                                class="micro-form-control form-control border mt-3"
                                id="name" 
                                type="text" 
                                name="name" 
                                required autofocus
                                placeholder="Your Name" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <input type="email"
                                class="micro-form-control form-control border mt-3"
                                value="{{ old('email') }}"
                                id="email" 
                                placeholder=" Email Address "
                                required
                                name="email"
                            >
                        </div>

                        <div class="form-group">
                            <input type="password" 
                                class="micro-form-control form-control border mt-3"
                                id="password"
                                name="password"
                                required 
                                autocomplete="new-password"
                                placeholder=" Password ">
                        </div>

                        <div class="form-group">
                            <input class="micro-form-control form-control border mt-3"
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation" required
                                placeholder=" Confirm Password "
                            >
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-sm mt-3 btn-danger w-100 p-2"> Submit </button>
                        </div>

                        <div class="form-group account-text">
                            <span> Already Have an Account? <a href="{{ route('login') }}" class="mt-5"> Login </a> Here </span>
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
