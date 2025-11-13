<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bimbingan;

class BimbinganPolicy
{
    /**
     * Determine whether the user can view the bimbingan.
     */
    public function view(User $user, Bimbingan $bimbingan): bool
    {
        // Mahasiswa can view their own bimbingan
        if ($user->isMahasiswa() && $bimbingan->mahasiswa_id === $user->id) {
            return true;
        }

        // Dosen can view bimbingan they guide
        if ($user->isDosen() && $bimbingan->dosen_id === $user->id) {
            return true;
        }

        // Admin can view all
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the bimbingan.
     */
    public function update(User $user, Bimbingan $bimbingan): bool
    {
        // Only dosen can update bimbingan (status, komentar, etc)
        return $user->isDosen() && $bimbingan->dosen_id === $user->id;
    }

    /**
     * Determine whether the user can create new bimbingan.
     */
    public function create(User $user): bool
    {
        // Both mahasiswa and dosen can create bimbingan records
        // (though different processes)
        return $user->isMahasiswa() || $user->isDosen();
    }
}
