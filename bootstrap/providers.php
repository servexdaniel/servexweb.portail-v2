<?php

return [
    App\Providers\AppServiceProvider::class,
    //App\Providers\FortifyServiceProvider::class, //Supprimer ce provider (FortifyServiceProvider) car on utilise nos propres controllers
    App\Providers\VoltServiceProvider::class,
];
