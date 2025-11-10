<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware untuk cek role user
 * Memastikan hanya user dengan role tertentu yang bisa akses route
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            // Jika belum login, redirect ke halaman login
            return redirect()->route('login')
                           ->with('error', 'Silakan login terlebih dahulu');
        }
        // Ambil role user yang sedang login
        $userRole = Auth::user()->role;

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Jika tidak punya akses, redirect dengan pesan error
            return redirect()->route('home')
                           ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Jika lolos pengecekan, lanjutkan request
        return $next($request);
    }
}
