@extends('frontend.layouts.master')

@section('content')
<!-- Account Area Starts-->

<section class="container-fluid account-area d-flex align-items-center" style="min-height: 50vh;">

    <div class="container">

        <div class="row">

            <div class="col-md-5 mx-auto">

                <div class="account-form">

                    <h3 class="text-center"> রিসেট পাসওয়ার্ড </h3>

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

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                    
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group">
                            <input class="micro-form-control form-control border mt-3" id="email" type="email"
                                name="email" 
                                value="{{ old('email', $request->email) }}" 
                                required 
                                autofocus
                                placeholder="ইমেইল অ্যাড্রেস">
                        </div>

                        <div class="form-group">
                            <input 
                                type="password" 
                                class="micro-form-control form-control border mt-3" 
                                placeholder=" নতুন পাসওয়ার্ড "
                                id="password"
                                name="password" 
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <input 
                            type="password" 
                            class="micro-form-control form-control border mt-3" 
                            placeholder=" কনফর্ম পাসওয়ার্ড "
                            id="password_confirmation"
                            name="password_confirmation" 
                            required
                            >
                        </div>


                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-sm mt-3 btn-danger w-100 p-2"> রিসেট পাসওয়ার্ড </button>
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
