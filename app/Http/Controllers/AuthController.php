<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Controller untuk mengelola authentication
 * Login, Logout, dan Register
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek remember me
        $remember = $request->has('remember');

        // Coba login dengan credentials yang diberikan
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role
            return redirect()->intended('/dashboard')
                           ->with('success', 'Selamat datang, ' . Auth::user()->name);
        }

        // Jika gagal, kembali dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan form register (hanya untuk testing)
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses register user baru
     */
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'nim_nip' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Buat user baru
        $user = User::create($validated);

        // Login otomatis setelah register
        Auth::login($user);

        return redirect('/dashboard')
                       ->with('success', 'Registrasi berhasil!');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
                       ->with('success', 'Anda telah logout');
    }
}
