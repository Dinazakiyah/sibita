<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $statusMahasiswa = $user->isMahasiswa() ? $user->statusMahasiswa : null;

        return view('home', [
            'user' => $user,
            'statusMahasiswa' => $statusMahasiswa
        ]);
    }
}
