<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function dashboard(): Factory|\Illuminate\Contracts\View\View
    {
        Log::channel('audit')->info('Admin visits admin.');
        $title         = (string) trans('Dashboard de l\'administration');
        return view('admin.dashboard', ['title' => $title]);
    }

    public function profile()
    {
        return view('admin.profile');
    }
}
