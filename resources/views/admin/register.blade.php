<x-guest-layout>
    <style>
        .topbar_logo {
            width: 200px;
        }
    
        .custom-btn {
            text-align: center !important;
            display: block !important;
            background: linear-gradient(45deg, red, #f9a146cc) !important;
        }
    </style>

    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{--
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
                @php
                $company = getCompanyProfile();
                @endphp
                @if($company && $company->dark_logo)
                <img src="{{ asset($company->dark_logo) }}" alt="" class="topbar_logo">
                @else
                <i class="fas fa-laugh-wink"></i>
                @endif
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('admin.register') }}">
            @csrf
            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4 custom-btn ">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
