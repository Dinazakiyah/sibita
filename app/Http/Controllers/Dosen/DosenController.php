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

    public function dashboard(): View
    {
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

    public function mahasiswa(): View
    {
        $dosen = Auth::user();
        $mahasiswa = $dosen->mahasiswaBimbingan()
            ->with('statusMahasiswa', 'bimbinganAsMahasiswa')
            ->paginate(15);

        return view('dosen.mahasiswa.index', compact('mahasiswa'));
    }

    public function showMahasiswa(User $mahasiswa): View
    {
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

    public function showBimbingan(Bimbingan $bimbingan): View
    {
        $this->authorize('view', $bimbingan);

        $submissions = $bimbingan->submissionFiles()
            ->with(['mahasiswa', 'comments.dosen'])
            ->latest('created_at')
            ->get();

        return view('dosen.Bimbingan.show', compact('bimbingan', 'submissions'));
    }

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

        $submission->update([
            'status' => $validated['status'],
            'dosen_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }

    public function approveSubmission(SubmissionFile $submission): RedirectResponse
    {
        $this->authorize('approve', $submission);

        $submission->update([
            'status' => 'approved',
            'dosen_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        $allApproved = $submission->bimbingan->submissionFiles()
            ->where('status', '!=', 'approved')
            ->doesntExist();

        if ($allApproved) {
            $submission->bimbingan->update(['status' => 'approved']);
        }

        return back()->with('success', 'Submission berhasil disetujui');
    }

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

    public function updateBimbinganStatus(Request $request, Bimbingan $bimbingan): RedirectResponse
    {
        $this->authorize('update', $bimbingan);

        $validated = $request->validate([
            'status' => 'required|in:pending,revisi,approved',
        ]);

        $bimbingan->update(['status' => $validated['status']]);

        return back()->with('success', 'Status bimbingan berhasil diperbarui');
    }

    public function history(): View
    {
        $dosen = Auth::user();

        $bimbingan = $dosen->bimbinganAsDosen()
            ->with(['mahasiswa', 'submissionFiles'])
            ->latest('created_at')
            ->paginate(20);

        return view('dosen.history', compact('bimbingan'));
    }
}
