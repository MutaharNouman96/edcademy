<x-guest-layout>
    {{-- <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form> --}}
<div class="container py-5 d-flex justify-content-center align-items-center flex-column">
    <div class="row justify-content-center g-5 w-100">
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <a href="{{ route('web.eudcator.signup') }}"
               class="text-decoration-none w-100"
               style="max-width: 22rem;">
                <div class="card text-center border-0 shadow-lg bg-primary text-white rounded-4 h-100"
                     style="min-height: 17rem; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 2rem;">
                    <div class="mb-4">
                        <i class="fas fa-chalkboard-teacher fa-4x"></i>
                    </div>
                    Educator Sign Up
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <a href="{{ route('student.signup') }}"
               class="text-decoration-none w-100"
               style="max-width: 22rem;">
                <div class="card text-center border-0 shadow-lg bg-success text-white rounded-4 h-100"
                     style="min-height: 17rem; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 2rem;">
                    <div class="mb-4">
                        <i class="fas fa-user-graduate fa-4x"></i>
                    </div>
                    Student Sign Up
                </div>
            </a>
        </div>
    </div>
</div>
<!-- Font Awesome required for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</x-guest-layout>
