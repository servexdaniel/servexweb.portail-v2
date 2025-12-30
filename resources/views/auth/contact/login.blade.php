<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account as contact')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @if (session('show_company_modal'))
            <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-xl font-bold mb-4">{{ session('show_company_modal.title') }}</h3>
                    <p class="text-gray-600 mb-6"> {{ session('show_company_modal.message') }} </p>
                    <form action="{{ route('contact.select-company') }}" method="POST">
                        @csrf
                        <select name="customer_number" class="w-full border rounded p-3 mb-4" required>
                            <option value="">--</option>
                            @foreach (session('servex_customers') as $cust)
                                <option value="{{ $cust['CcCustomerNumber'] }}">
                                    {{ $cust['CcName'] }}
                                </option>
                            @endforeach
                        </select>
                        <!-- INPUT HIDDEN : on repasse le username (ou email) -->
                        <input type="hidden" name="username" value="{{ session('servex_temp_username') }}">

                        <!-- INPUT HIDDEN : on repasse le mot de passe (haché ou non selon ta logique) -->
                        <input type="hidden" name="password" value="{{ session('servex_temp_password') }}">

                        <!-- OU plus sécurisé : un token temporaire -->
                        <input type="hidden" name="auth_token" value="{{ session('servex_auth_token') }}">
                        <div class="flex gap-3">
                            <button type="button" @click="open = false"
                                class="flex-1 px-6 py-3 border rounded-lg hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Continuer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input name="username" :label="__('Username')" :value="old('username')" type="text" required
                autofocus autocomplete="username" placeholder="username" />

            {{-- <flux:input name="login" :label="__('Email address or Username')" :value="old('login')" type="text"
                required autofocus autocomplete="login" placeholder="username or email" /> --}}

            <!-- Password -->
            <div class="relative">
                <flux:input name="password" :label="__('Password')" type="password" required
                    autocomplete="current-password" :placeholder="__('Password')" viewable />

                @if (Route::has('contact.password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('contact.password.request')"
                        wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('contact.register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('contact.register')" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts.auth>
