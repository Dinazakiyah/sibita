<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\SubmissionFile;
use App\Models\Comment;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Show dosen dashboard
     */
    public function dashboard(): View
    {
        /** @var User $dosen */
        $dosen = Auth::user();

        $totalMahasiswa = $dosen->mahasiswaBimbingan()->count();

        $bimbinganPending = $dosen->bimbinganAsDosen()
            ->where('status', 'pending')
            ->with(['mahasiswa', 'submissionFiles'])
            ->latest('created_at')
            ->get();

        $pendingReview = SubmissionFile::where('dosen_id', $dosen->id)
            ->where('status', 'submitted')
            ->count();

        $mahasiswaBimbingan = $dosen->mahasiswaBimbingan()
            ->with(['statusMahasiswa', 'bimbinganAsMahasiswa'])
            ->get();

        $recentBimbingan = $dosen->bimbinganAsDosen()
            ->with(['mahasiswa', 'submissionFiles.comments'])
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('dosen.dashboard', compact(
            'totalMahasiswa',
            'pendingReview',
            'bimbinganPending',
            'mahasiswaBimbingan',
            'recentBimbingan'
        ));
    }

    /**
     * Show list of mahasiswa that dosen pembimbing
     */
    public function mahasiswa(): View
    {
    /** @var User $dosen */
    $dosen = Auth::user();
        $mahasiswa = $dosen->mahasiswaBimbingan()
            ->with('statusMahasiswa', 'bimbinganAsMahasiswa')
            ->paginate(15);

        return view('dosen.mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Show detail mahasiswa
     */
    public function showMahasiswa(User $mahasiswa): View
    {
        // Check if user is dosen pembimbing of this mahasiswa
    /** @var User $current */
    $current = Auth::user();

        if ($current->isDosen()) {
            $isBimbingan = $current->mahasiswaBimbingan()
                ->where('users.id', $mahasiswa->id)
                ->exists();

            if (!$isBimbingan) {
                abort(403, 'Anda tidak memiliki akses ke mahasiswa ini');
            }
        }

        $bimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with(['dosen', 'submissionFiles.comments'])
            ->paginate(10);

        return view('dosen.mahasiswa.dosen lihat detail mahasiswa', compact('mahasiswa', 'bimbingan'));
    }

    /**
     * Show bimbingan detail
     */
    public function showBimbingan(Bimbingan $bimbingan): View
    {
        $this->authorize('view', $bimbingan);

        $submissions = $bimbingan->submissionFiles()
            ->with(['mahasiswa', 'comments.dosen'])
            ->latest('created_at')
            ->get();

        return view('dosen.Bimbingan.show', compact('bimbingan', 'submissions'));
    }

    /**
     * Show submission detail for review
     */
    public function reviewSubmission(SubmissionFile $submission): View
    {
        $this->authorize('review', $submission);

        $comments = $submission->comments()
            ->with('dosen')
            ->orderBy('is_pinned', 'desc')
            ->latest('created_at')
            ->get();

        $submission->load('mahasiswa');

        return view('dosen.submissions.review', compact('submission', 'comments'));
    }

    /**
     * Add comment to submission
     */
    public function addComment(Request $request, SubmissionFile $submission): RedirectResponse
    {
        $this->authorize('addComment', $submission);

        $validated = $request->validate([
            'comment' => 'required|string|max:5000',
            'status' => 'required|in:pending,approved,revision_needed',
            'priority' => 'required|in:0,1,2',
            'is_pinned' => 'boolean',
        ]);

        $comment = Comment::create([
            'submission_id' => $submission->id,
            'dosen_id' => Auth::id(),
            'comment' => $validated['comment'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'is_pinned' => $validated['is_pinned'] ?? false,
        ]);

        // Update submission status
        $submission->update([
            'status' => $validated['status'],
            'dosen_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Approve submission
     */
    public function approveSubmission(SubmissionFile $submission): RedirectResponse
    {
        $this->authorize('approve', $submission);

        $submission->update([
            'status' => 'approved',
            'dosen_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Update bimbingan status if all submissions approved
        $allApproved = $submission->bimbingan->submissionFiles()
            ->where('status', '!=', 'approved')
            ->doesntExist();

        if ($allApproved) {
            $submission->bimbingan->update(['status' => 'approved']);
        }

        return back()->with('success', 'Submission berhasil disetujui');
    }

    /**
     * Reject submission
     */
    public function rejectSubmission(Request $request, SubmissionFile $submission): RedirectResponse
    {
        $this->authorize('reject', $submission);

        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $submission->update([
            'status' => 'rejected',
            'dosen_id' => Auth::id(),
            'dosen_notes' => $validated['reason'],
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Submission berhasil ditolak');
    }

    /**
     * Update bimbingan status
     */
    public function updateBimbinganStatus(Request $request, Bimbingan $bimbingan): RedirectResponse
    {
        $this->authorize('update', $bimbingan);

        $validated = $request->validate([
            'status' => 'required|in:pending,revisi,approved',
        ]);

        $bimbingan->update(['status' => $validated['status']]);

        return back()->with('success', 'Status bimbingan berhasil diperbarui');
    }

    /**
     * Get bimbingan history
     */
    public function history(): View
    {
    /** @var User $dosen */
    $dosen = Auth::user();

        $bimbingan = $dosen->bimbinganAsDosen()
            ->with(['mahasiswa', 'submissionFiles'])
            ->latest('created_at')
            ->paginate(20);

        return view('dosen.history', compact('bimbingan'));
    }
}
