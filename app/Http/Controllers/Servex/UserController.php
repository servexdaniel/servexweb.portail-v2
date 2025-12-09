<?php

namespace App\Http\Controllers\Servex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function showLoginForm()
    {
        return view('auth.user.login');
    }

    public function create()
    {
        return view('auth.user.register'); // ou ta vue avec Flux
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Très important : connexion avec le guard "web"
        Auth::guard('web')->login($user);

        dd("User registered and logged in");

        // Redirection après inscription (dashboard user, etc.)
        return redirect()->intended(route('dashboard',['language' => app()->getLocale()]));
    }

    public function showForgetPasswordForm()
    {
        return view('auth.user.forget-password');
    }

    public function showResetPasswordForm($token, Request $request)
    {
        return view('auth.user.reset-password', ['token' => $token, 'request' => $request]);
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
        return view('user.dashboard'); // ou ta vue avec Flux
    }

    public function profile()
    {
        return view('user.profile'); // ou ta vue avec Flux
    }

    public function logout(Request $request)
    {
        dd("logout");
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }
}
