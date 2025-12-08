<?php

namespace App\Http\Controllers\Servex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function register()
    {
        dd("register");
    }

    public function showForgetPasswordForm()
    {
        return view('auth.servex.forget-password');
    }

    public function showResetPasswordForm($token, Request $request)
    {
        return view('auth.servex.reset-password', ['token' => $token, 'request' => $request]);
    }

    public function submitForgetPasswordForm(Request $request)
    {
        dd("submitForgetPasswordForm");
    }

    public function submitResetPasswordForm(Request $request)
    {
        dd("submitResetPasswordForm");
    }

    public function logout(Request $request)
    {
        dd("logout");
        auth('contact')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('contact.login');
    }
}
