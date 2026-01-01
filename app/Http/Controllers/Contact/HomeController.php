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
        Log::channel('audit')->info('Contact visits index.');
        $header         = (string) trans('Log in to your account as contact');
        $description   = (string) trans('Enter your email and password below to log in');
        return view('contact.index', ['header' => $header, 'description' => $description]);
    }

    public function forgetPassword(): Factory|\Illuminate\Contracts\View\View
    {
        Log::channel('audit')->info('Contact visits forget password.');
        $header         = (string) trans('Recover my password');
        $description   = (string) trans('Please enter your email address below to receive instructions for resetting password.');
        return view('contact.forget-password', ['header' => $header, 'description' => $description]);
    }

    
}
