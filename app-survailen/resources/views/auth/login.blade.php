<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username Address -->
        <div class="form-group-margin">
            <x-input-label for="Username" :value="__('Username')" />
            <div class="input-wrapper">
                <span class="input-icon-left">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <x-text-input id="Username" class="custom-text-input" type="text" name="Username" :value="old('Username')" required autofocus autocomplete="Username" placeholder="username" />
            </div>
            <x-input-error :messages="$errors->get('Username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group-margin" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="input-wrapper">
                <span class="input-icon-left">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <x-text-input id="password" class="custom-text-input input-password"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" placeholder="password" />
                                
                <button type="button" @click="show = !show" class="password-toggle-btn">
                    <!-- Icon when password is hidden (Eye crossed) -->
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    <!-- Icon when password is shown (Eye open) -->
                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="remember-me-container">
            <label for="remember_me" class="remember-me-label">
                <input id="remember_me" type="checkbox" class="remember-me-checkbox" name="remember">
                <span class="remember-me-text">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="form-actions-container">
            <button type="submit" class="login-btn">
                {{ __('LOGIN') }}
            </button>
        </div>
    </form>
</x-guest-layout>
