<?php

namespace App\Policies;

use App\Models\SubmissionFile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubmissionFilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubmissionFile $submissionFile): bool
    {
        // Mahasiswa can view their own submissions
        if ($user->isMahasiswa() && $submissionFile->mahasiswa_id === $user->id) {
            return true;
        }

        // Dosen can view submissions of their students
        if ($user->isDosen()) {
            $bimbingan = $submissionFile->bimbingan;
            return $bimbingan && $bimbingan->dosen_id === $user->id;
        }

        // Admin can view all
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can review the submission.
     */
    public function review(User $user, SubmissionFile $submissionFile): bool
    {
        // Only dosen can review
        if (!$user->isDosen()) {
            return false;
        }

        // Dosen can review submissions from their students
        $bimbingan = $submissionFile->bimbingan;
        return $bimbingan && $bimbingan->dosen_id === $user->id;
    }

    /**
     * Determine whether the user can approve the submission.
     */
    public function approve(User $user, SubmissionFile $submissionFile): bool
    {
        return $this->review($user, $submissionFile);
    }

    /**
     * Determine whether the user can reject the submission.
     */
    public function reject(User $user, SubmissionFile $submissionFile): bool
    {
        return $this->review($user, $submissionFile);
    }

    /**
     * Determine whether the user can add comment to submission.
     */
    public function addComment(User $user, SubmissionFile $submissionFile): bool
    {
        return $this->review($user, $submissionFile);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only mahasiswa can upload/create submissions
        return $user->isMahasiswa();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubmissionFile $submissionFile): bool
    {
        // Mahasiswa can't update submissions (only reupload new ones)
        // Dosen can update submission status through review
        return $user->isDosen() && $this->review($user, $submissionFile);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubmissionFile $submissionFile): bool
    {
        // Only admin can delete submissions
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubmissionFile $submissionFile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubmissionFile $submissionFile): bool
    {
        return false;
    }
}
