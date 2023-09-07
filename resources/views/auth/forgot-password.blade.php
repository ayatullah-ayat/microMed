{{-- <x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout> --}}



@extends('frontend.layouts.master')

@section('content')
    <!-- Account Area Starts-->
    
    <section class="container-fluid account-area d-flex align-items-center" style="min-height: 50vh;">
    
        <div class="container">
    
            <div class="row">
    
                <div class="col-md-5 mx-auto">
    
                    <div class="account-form">
    
                        <h3 class="text-center"> ফরগেট পাসওয়ার্ড </h3>

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
    
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
    
                            <div class="form-group">
                                <input class="micro-form-control form-control border mt-3"
                                    id="email"
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus
                                    placeholder="ইমেইল অ্যাড্রেস">
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-sm mt-3 btn-danger w-100 p-2"> পাঠিয়ে দিন </button>
                            </div>
    
                        </form>
    
                    </div>
    
                </div>
    
            </div>
    
        </div>
    
    </section>
    
    <!-- Account Area Starts-->
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/frontend/css/login.css') }}">
@endpush