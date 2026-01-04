<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(
            static function ($request, $next) {
                app('view')->share('title', (string) trans('Settings - Call Columns'));
                if(Auth::guard('contact')->check()) {
                    app('view')->share('isManager', Auth::guard('contact')->user()->CcIsManager);
                } else {
                    app('view')->share('isManager', false);
                }

                return $next($request);
            }
        );
        //$this->middleware(AdminMiddleware::class . ':contact')->only(['dashboard', 'profile']);
        $this->middleware('admin:contact')->only(['callSettings']);
    }

    public function callSettings()
    {
        Log::channel('audit')->info('Admin visits settings.calls.grid.columns.');
        return view('settings.calls.grid.columns');
    }
}
