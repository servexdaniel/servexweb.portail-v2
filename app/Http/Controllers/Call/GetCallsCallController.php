<?php

namespace App\Http\Controllers\Call;

use view;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetCallsCallController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(
            static function ($request, $next) {
                app('view')->share('title', (string) trans('Calls'));
                if(Auth::guard('contact')->check()) {
                    app('view')->share('isManager', Auth::guard('contact')->user()->CcIsManager);
                } else {
                    app('view')->share('isManager', false);
                }

                return $next($request);
            }
        );
        //$this->middleware(AdminMiddleware::class . ':contact')->only(['dashboard', 'profile']);
        $this->middleware('contact:contact')->except([]);
    }

    public function index()
    {
        Log::channel('audit')->info('Contact visits calls index.');
        return view('calls.index');
    }
}
