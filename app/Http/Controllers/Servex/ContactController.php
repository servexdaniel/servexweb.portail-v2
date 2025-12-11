<?php

namespace App\Http\Controllers\Servex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use \App\Http\Mobility\Modules\ServexAuth;

class ContactController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        /*
        $request->validate([
            'login'    => 'required|string',              // champ unique dans le formulaire
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        // On construit les identifiants dynamiquement
        $login    = $request->input('login');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // On cherche d'abord si c’est un email ou un username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field    => $login,
            'password'=> $password,
        ];
        */

        $contact = (new ServexAuth("", "daniel", $credentials['password']))->authenticate();

        dd($contact);

        if($contact != null)
        {
            //$test0 = Auth::guard('contact')->attempt($credentials, $request->filled('remember'));
            //$test = Auth::guard('contact')->loginUsingId($contact->id);

            // Avec la case "Se souvenir de moi" cochée ou non
            $remember = $request->filled('remember'); // ou $request->has('remember') ou $request->filled('remember')

            Auth::guard('contact')->login($contact, $remember);

            if (Auth::guard('contact')->check()) {
                session()->regenerate();

                return redirect()->intended(route('contact.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            //'login' => 'Les identifiants fournis sont incorrects.',
        ])->onlyInput('email');
    }
    public function showLoginForm()
    {
        return view('auth.contact.login');
    }

    public function create()
    {
        return view('auth.contact.register'); // ou ta vue avec Flux
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
        return redirect()->intended(route('contact.dashboard', ['language' => app()->getLocale()]));
    }

    public function showForgetPasswordForm()
    {
        return view('auth.contact.forget-password');
    }

    public function showResetPasswordForm($token, Request $request)
    {
        return view('auth.contact.reset-password', ['token' => $token, 'request' => $request]);
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

    public function profile()
    {
        return view('contact.profile'); // ou ta vue avec Flux
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
