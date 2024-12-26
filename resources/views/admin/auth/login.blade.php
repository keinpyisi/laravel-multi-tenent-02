<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.users.check_login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="login_id"  :value="__('ユーザーID')"/>
            <x-text-input id="login_id" class="block mt-1 w-full" type="text" name="login_id" :value="old('login_id')" required autofocus autocomplete="login_id"  placeholder="ログインID(半角英数)"/>
            <x-input-error :messages="$errors->get('login_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('パスワード')" />

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
