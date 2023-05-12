<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="{{auth()->user()->profile_image}}" class="w-50 h-40 rounded-full mx-auto" alt="" >
            </a>
        </x-slot>

        <div class="mb-4 text-md text-red-600">
            {{ __('message.secret') }}
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Enter Your current Password Here')" />

                <x-input id="password" type="password"  name="password"
                class="block mt-1 w-full" required autocomplete="current-password" />
                    @error('password')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
            </div>

            <div>
                <x-label for="secret_password" :value="__('Enter the secret Password Here')" class="mt-5 w-full"/>

                <x-input id="secret_password" name="secret_password"  type="password"
                class="block mt-1 w-full"  required autocomplete="current-password" />
                    @error('secret_password')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
            </div>
            <div class="flex justify-between mt-6">
                <a href="{{url()->previous()}}" class="btn btn-link inline-flex items-center px-4 py-2
                     bg-blue-700 rounded-md font-semibold   text-white uppercase hover:bg-purple-800  ">
                    {{ __('Return') }}
                </a>

                <x-button>
                    {{ __('Forward') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
