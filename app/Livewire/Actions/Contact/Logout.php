<?php

namespace App\Livewire\Actions\Contact;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Servex\Traits\UsesDomainTrait;

class Logout
{
    use UsesDomainTrait;
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        /*
        Auth::guard('contact')->logout();

        Session::invalidate();
        Session::regenerateToken();
        */



        //Annuler la permission de visualiser les appels pour le contact qui se deconnecte
        $contact_id = Auth::guard('contact')->user()->id;
        $contact = Contact::find($contact_id);

        //Deconnexion
        Auth::guard('contact')->logout();
        $request = request();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        $client   = $this->getCurrentTenant();

        //Enlever la session de la base de données
        DB::table('sessions')->where('id', $contact->sessionid)->delete();

        //Supprimer les informations du contact
        $contact->delete();

        //return redirect()->route('welcome', ['language' => app()->getLocale()]);
        return redirect()->route('home', ['language' => app()->getLocale()]);

    }
}
