<?php

namespace App\Http\Controllers\Servex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ContactController extends Controller
{
    public function login()
    {
        dd("login");
    }

    public function showLoginForm()
    {
        return view('auth.servex.login');
    }

    public function create()
    {
        return view('auth.servex.register'); // ou ta vue avec Flux
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:servex_contacts,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $contact = Contact::create([
            'CcName'     => $request->name,
            'CuName'     => $request->name,
            'username'  => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Très important : connexion avec le guard "contact"
        Auth::guard('contact')->login($contact);

        // Redirection après inscription (dashboard contact, etc.)
        return redirect()->intended(route('contact.dashboard'));
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

    public function dashboard()
    {
        return view('contact.dashboard'); // ou ta vue avec Flux
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
