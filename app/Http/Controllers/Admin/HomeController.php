<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminMiddleware;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(
            static function ($request, $next) {
                app('view')->share('title', (string) trans('Panneau d\'administration'));
                app('view')->share('isManager', Auth::guard('contact')->user()->CcIsManager);

                return $next($request);
            }
        );
        $this->middleware(AdminMiddleware::class . ':contact')->only(['dashboard', 'profile']);
    }

    public function dashboard(): Factory|\Illuminate\Contracts\View\View
    {
        Log::channel('audit')->info('Admin visits admin.');
        return view('admin.dashboard');
    }

    public function profile()
    {
        return view('admin.profile');
    }
}
