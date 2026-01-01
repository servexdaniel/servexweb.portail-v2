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
            <form class="form-auth-small" method="POST" action="{{ route('contact.login.store') }}">
                @csrf
                <div class="form-group">
                    <label for="username" class="control-label sr-only">{{ __('Username / Email address') }}</label>
                    <input type="email" class="form-control" id="username" value="{{ old('username') }}" name="username"
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
