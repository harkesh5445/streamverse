<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50">
        <div class="w-full max-w-md bg-white rounded-xl shadow p-8 mt-8">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-900">Sign In to StreamYard Clone</h2>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">Log in</button>
            </form>

            <div class="mt-6 text-center">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold ml-1">Register</a>
            </div>
        </div>
    </div>
</x-guest-layout>
