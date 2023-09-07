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

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('admin.password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="custom-btn w-full">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
