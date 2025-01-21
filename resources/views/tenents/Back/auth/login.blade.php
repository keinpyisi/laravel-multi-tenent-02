<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h2 class="text-2xl font-semibold text-center mb-6">{{ $tenant_name }} ログイン</h2>
    <form method="POST" action="{{ route('tenant.users.check_login', ['tenant' => $tenant_name]) }}">
        @csrf
        <input type="text" id="tenant_name" name="tenant_name" value="{{ $tenant_name }}" hidden>
        <!-- Email Address -->
        <div>
            <x-input-label for="login_id" :value="__('ログインID')" />
            <x-text-input id="text" class="block mt-1 w-full" type="text" name="login_id" :value="old('login_id')" required autofocus autocomplete="login_id" placeholder="ログインID(半角英数)" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            placeholder="パスワード(半角英数)" 
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
