<?php

namespace App\Http\Controllers\Contact;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class HomeController extends Controller
{
    /**
     * Index of the admin.
     *
     * @return Factory|View
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Factory|\Illuminate\Contracts\View\View
    {
        Log::channel('audit')->info('User visits admin index.');
        $header         = (string) trans('Log in to your account as contact');
        $description   = (string) trans('Enter your email and password below to log in');
        return view('contact.index', ['header' => $header, 'description' => $description]);
    }
}
