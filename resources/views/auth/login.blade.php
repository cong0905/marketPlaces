<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu')" />

            <div x-data="{ show: false }" class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-10"
                                ::type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility' : 'visibility_off'"></span>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-surface border-outline-variant text-primary shadow-sm focus:ring-primary" name="remember">
                <span class="ms-2 text-body-sm font-body-sm text-on-surface-variant">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors focus:outline-none focus:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="relative flex py-5 items-center">
        <div class="flex-grow border-t border-outline-variant"></div>
        <span class="flex-shrink mx-4 text-body-sm font-body-sm text-on-surface-variant">Hoặc đăng nhập bằng</span>
        <div class="flex-grow border-t border-outline-variant"></div>
    </div>

    <div class="mt-2">
        <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 w-full py-3 px-4 rounded-xl border border-outline-variant hover:border-primary hover:bg-surface-container-low transition-all duration-300 shadow-sm font-body-md text-on-surface">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.35,11.1H12v2.7h5.38c-0.24,1.28 -0.96,2.37 -2.04,3.1v2.6h3.3c1.93,-1.78 3.04,-4.4 3.04,-7.4c0,-0.72 -0.07,-1.4 -0.19,-2.0z" fill="#4285F4" />
                <path d="M12,20.7c2.43,0 4.47,-0.8 5.96,-2.2l-3.3,-2.6c-0.9,0.6 -2.07,0.98 -3.3,0.98 -2.34,0 -4.33,-1.58 -5.04,-3.7H3v2.6c1.5,3.0 4.6,5.0 8.0,5.0z" fill="#34A853" />
                <path d="M6.96,13.18C6.78,12.68 6.7,12.15 6.7,11.6c0,-0.55 0.08,-1.08 0.26,-1.58V7.4H3C2.36,8.68 2,10.1 2,11.6c0,1.5 0.36,2.92 1,4.2l3.96,-3.02z" fill="#FBBC05" />
                <path d="M12,5.18c1.3,0 2.5,0.45 3.4,1.3l2.6,-2.6C16.4,2.3 14.4,1.5 12,1.5c-3.4,0 -6.5,2.0 -8.0,5.0l3.96,3.02c0.7,-2.12 2.7,-3.7 5.04,-3.7z" fill="#EA4335" />
            </svg>
            <span class="font-medium">Tiếp tục với Google</span>
        </a>
    </div>
</x-guest-layout>
