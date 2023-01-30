<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                @isset($info)
                <img src="{{$info->company_logo}}" alt="" srcset="">
                @endisset
            </a>
        </x-slot>

        <div class="mb-4 text-lg text-gray-800">
            {{ __('Forgot password? No problem. Enter your email and we will send you a password reset link .') }}
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
</x-guest-layout>
