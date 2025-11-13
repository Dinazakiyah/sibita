<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profile user
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $statusMahasiswa = $user->isMahasiswa() ? $user->statusMahasiswa : null;

        return view('profile.show', [
            'user' => $user,
            'statusMahasiswa' => $statusMahasiswa
        ]);
    }

    /**
     * Menampilkan form edit profile
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.edit', [
            'user' => $user
        ]);
    }

    /**
     * Menyimpan perubahan profile
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'nim_nip' => 'nullable|string|max:20',
        ]);

        // Update user
        $user->update($validated);

        return redirect()->route('profile.show')
                       ->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Menampilkan form ubah password
     */
    public function editPassword()
    {
        return view('profile.change-password');
    }

    /**
     * Menyimpan perubahan password
     */
    public function updatePassword(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Update password
        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile.show')
                       ->with('success', 'Password berhasil diubah!');
    }
}
