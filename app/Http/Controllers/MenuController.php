<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Show a concise actionable menu for the current user role
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('menu', compact('user'));
    }
}
