@extends('layouts.v1.guest')

@section('content')
LOGIN
    {{-- <div class="card">
        <div class="header">
            @isset($header)
                <p class="lead">{{ $header }}</p>
            @endisset
        </div>
        <div class="body">
            <form class="form-auth-small" action="index.html">
                <div class="form-group">
                    <label for="signin-email" class="control-label sr-only">Email</label>
                    <input type="email" class="form-control" id="signin-email" value="user@domain.com" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="signin-password" class="control-label sr-only">Password</label>
                    <input type="password" class="form-control" id="signin-password" value="thisisthepassword"
                        placeholder="Password">
                </div>
                <div class="form-group clearfix">
                    <label class="fancy-checkbox element-left">
                        <input type="checkbox">
                        <span>Remember me</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                <div class="bottom">
                    <span class="helper-text m-b-10"><i class="fa fa-lock"></i><a
                            href="{{ route('contact.forget-password') }}">Forgot password?</a></span>
                </div>
            </form>
        </div>
    </div> --}}
@endsection
