<?php

namespace App\Http\Controllers\Servex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use \App\Http\Mobility\Modules\ServexAuth;
use App\Servex\Traits\UsesDomainTrait;
use App\Http\Mobility\Modules\ServexSynchro;

class ContactController extends Controller
{
    use UsesDomainTrait;
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username'    => 'required|string',
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

        $auth = (new ServexAuth("", $credentials['username'], $credentials['password']));
        $result = $auth->authenticate();

        // On cherche d'abord si c’est un email ou un username
        $fieldType = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if( is_null($result)) {
            return back()->withErrors(['username' => 'Identifiants incorrects']);
        }

        // Cas : plusieurs compagnies → on affiche un modal/page de sélection
        if (is_array($result) && $result['type'] === 'choose_company') {
            // On reste sur la page de login et on ouvre un modal pour choisir la compagnie
            return redirect()->back()->with('show_company_modal', [
                'show'    => true,
                'type'    => 'choose-company',
                'title'   => 'Choisissez votre compagnie',
                'message' => 'Vous avez accès à plusieurs compagnies Servex.',
                'credentials' => $credentials
            ]);
        }

        // Cas : un seul client → connexion directe
        if (is_array($result) && $result['type'] === 'single_company') {
            $this->loginContact($request, $result['contact']);
            return redirect()->intended(route('contact.dashboard'));
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
            //'login' => 'Les identifiants fournis sont incorrects.',
        ])->onlyInput('username');
    }


    public function selectCompany(Request $request)
    {
        //$request->validate(['customer_number' => 'required']);

        $request->validate([
            'customer_number' => 'required',
            'auth_token'      => 'required',
            'username'    => 'required',
            'password' => 'required',
        ]);

        // Vérifie que le token est valide et non expiré
        if (session('servex_auth_token') !== $request->auth_token) {
            return back()->withErrors(['error' => 'Session expirée']);
        }

        $customers = session('servex_customers', []);
        $selected  = collect($customers)->firstWhere('CcCustomerNumber', $request->customer_number);

        if (!$selected) {
            return redirect()->route('contact.login');
        }

        // On recrée les infos avec uniquement ce client
        $user_info = session('servex_user_info');
        $user_info['CcCustomerNumber'] = $selected['CcCustomerNumber'];
        $user_info['CcName'] = $selected['CcName'];

        $auth = (new ServexAuth($request->customer_number, $request->username, $request->password));
        $result = $auth->authenticate();

        if (is_array($result) && $result['type'] === 'single_company') {
            $this->loginContact($request, $result['contact']);
            return redirect()->intended(route('contact.dashboard'));
        }
        return back()->withErrors(['error' => 'Erreur lors de la connexion']);
    }

    /**
     * Crée ou met à jour un Contact à partir des informations retournées par Servex Mobility.
     *
     * @param  array  $user_info  Tableau contenant les données Servex (CcName, CcEmail, etc.)
     * @param  string|null  $specificCustomerNumber  Optionnel : force un CcCustomerNumber précis (utilisé lors du choix de compagnie)
     * @return Contact
     */
    private function createContactFromUserInfo(array $user_info, ?string $specificCustomerNumber = null): Contact
    {
        // Récupération du tenant actuel (multitenancy)
        $tenant = $this->getCurrentTenant();

        dd($user_info);
        // Recherche ou création du contact (évite les doublons)
        $contact = Contact::firstOrNew([
            'customer_id' => $tenant->id,
            'username'    => $this->username,
            'CuNumber'    => $specificCustomerNumber ?? $this->ccCustomerNumber,
        ]);

        // Gestion du booléen CcIsManager ("OUI"/"NON" ou true/false selon Servex)
        $ccIsManager = filter_var($user_info['CcIsManager'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // Nettoyage très robuste des chaînes venant de l'AS/400 (encodage Windows-1252 → UTF-8 + entités HTML)
        $clean = function ($value) {
            if (!is_string($value)) {
                return '';
            }
            // html_entity_decode + suppression des caractères corrompus + UTF-8 propre
            return mb_convert_encoding(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'Windows-1252'), 'UTF-8', 'Windows-1252');
        };

        // Date/heure de connexion dans le fuseau horaire de l'application
        $now = now()->timezone(config('app.timezone'));

        // =======================
        // Remplissage du modèle
        // =======================
        $contact->customer_id       = $tenant->id;
        $contact->username          = $this->username;
        $contact->password          = Hash::make($this->password); // toujours rehaché
        $contact->CuNumber          = $specificCustomerNumber ?? $this->ccCustomerNumber;
        $contact->connected_at      = $now;

        // Nom de la compagnie : si l'utilisateur est gestionnaire → on prend celui renvoyé par Servex
        $contact->CuName = $ccIsManager
            ? $clean($user_info['CuName'] ?? $tenant->name)
            : $tenant->name;

        $contact->CcName            = $this->username;
        $contact->CcIsManager       = $ccIsManager;
        $contact->CcPortailAdmin    = filter_var($user_info['CcPortailAdmin'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $contact->CcPhoneNumber     = $clean($user_info['CcPhoneNumber'] ?? '');
        $contact->CcEmail           = $clean($user_info['CcEmail'] ?? '');
        $contact->email             = $contact->CcEmail; // email de connexion

        $contact->CcPhoneExt           = $clean($user_info['CcPhoneExt'] ?? '');
        $contact->CcCellNumber      = $clean($user_info['CcCellNumber'] ?? '');

        // Informations de debug / traçabilité Servex (très utiles pour le support)
        $contact->LoginSuccess      = $clean($user_info['LoginSuccess'] ?? '');
        $contact->ReasonLogin       = $clean($user_info['ReasonLogin'] ?? '');

        return $contact;
    }

    /**
     * Connecte un contact avec le guard 'contact', régénère la session et met à jour le sessionid.
     *
     * @param Request $request
     * @param Contact $contact
     * @return void
     */
    private function loginContact(Request $request, Contact $contact): void
    {
        $remember = $request->filled('remember');
        Auth::guard('contact')->login($contact, $remember);
        if (Auth::guard('contact')->check()) {
            session()->regenerate();
            $contact->sessionid = Session::getId();
            $contact->save();

            (new ServexSynchro())->getCustomerInfo($contact->CuNumber, $contact->id);
        }
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
