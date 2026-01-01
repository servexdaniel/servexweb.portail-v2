@extends('layouts.v1.guest')

@section('content')
    <div class="card">
        <div class="header">
            @isset($header)
                <p class="lead">{{ $header }}</p>
            @endisset
            @isset($description)
                <p>{{ $description }}</p>
            @endisset
        </div>
        <div class="body">
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
            <form class="form-auth-small" method="POST" action="{{ route('contact.login.store') }}">
                @csrf
                <div class="form-group">
                    <label for="username" class="control-label sr-only">{{ __('Username / Email address') }}</label>
                    <input type="text" class="form-control" id="username" value="{{ old('username') }}" name="username"
                        placeholder="{{ __('Username / Email address') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="signin-password" class="control-label sr-only">{{ __('Password') }}</label>
                    <input type="password" class="form-control" id="signin-password" placeholder="{{ __('Password') }}"
                        name="password" required viewable>
                </div>
                <div class="form-group clearfix">
                    <label class="fancy-checkbox element-left">
                        <input type="checkbox" checked="old('remember')" name="remember">
                        <span>{{ __('Remember me') }}</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                <div class="bottom">
                    <span class="helper-text m-b-10"><i class="fa fa-lock"></i><a
                            href="{{ route('contact.forget-password') }}">Forgot password?</a></span>
                </div>
            </form>
        </div>
    </div>
@endsection
