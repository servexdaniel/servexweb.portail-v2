<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Contact extends Authenticatable
{
    use /*\Illuminate\Auth\Authenticatable,*/ HasFactory;
    protected $table = 'servex_contacts';

    protected $guard = 'contact';

    protected $fillable = [
        'id',
        'sessionid',
        'customer_id',
        'connected_at',
        'username',
        'email',
        'password',
        'CcUnique',
        'CcName',
        'CuNumber',
        'CuName',
        'CuAddress',
        'CuCity',
        'CuPostalCode',
        'CcIsManager',
        'CcPortailAdmin',
        'CcPhoneNumber',
        'CcPhoneExt',
        'CcCellNumber',
        'CcEmail',
        'LoginSuccess',
        'ReasonLogin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // /**
    //  * Permet de se connecter avec username OU email
    //  */
    // public function getAuthIdentifierName()
    // {
    //     return 'username'; // ou 'email' si vous préférez, mais on va le surcharger dynamiquement
    // }

    // /**
    //  * Surcharge de la méthode qui récupère la valeur du champ de connexion
    //  */
    // public function getAuthIdentifier()
    // {
    //     // On regarde d'abord si c'est un email
    //     if (filter_var($this->username, FILTER_VALIDATE_EMAIL)) {
    //         return $this->email;
    //     } else {
    //         return $this->username;
    //     }
    // }

    // /**
    //  * Méthode utilisée pour la session
    //  */
    // public function getAuthIdentifierForSession(): int
    // {
    //     return $this->getKey(); // toujours l'ID numérique (1, 42, 123...)
    // }

    // /**
    //  * Méthode la plus simple et la plus utilisée (recommandée)
    //  */
    // public function findForPassport($identifier)
    // {
    //     return $this->where('username', $identifier)
    //                 ->orWhere('email', $identifier)
    //                 ->first();
    // }

    // // Variante encore plus propre (Laravel 9+)
    // public function findAndValidateForPassport($identifier)
    // {
    //     return $this->where('username', $identifier)
    //                 ->orWhere('email', $identifier)
    //                 ->first();
    // }
}
