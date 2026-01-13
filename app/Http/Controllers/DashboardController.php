<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function admin(): View
    {
        return view('dashboard.admin');
    }

    /**
     * Display the worker dashboard.
     */
    public function worker(): View
    {
        return view('dashboard.worker');
    }

    /**
     * Display the user dashboard.
     */
    public function user(): View
    {
        return view('dashboard.user');
    }
}
