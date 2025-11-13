<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\SubmissionFile;
use App\Models\Comment;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Show mahasiswa dashboard
     */
    public function dashboard(): View
    {
        /** @var User $mahasiswa */
        $mahasiswa = \Illuminate\Support\Facades\Auth::user();

        $stats = [
            'total_bimbingan' => $mahasiswa->bimbinganAsMahasiswa()->count(),
            'pending_submissions' => SubmissionFile::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'submitted')
                ->count(),
            'approved_submissions' => SubmissionFile::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'approved')
                ->count(),
            'status_mahasiswa' => $mahasiswa->statusMahasiswa,
        ];

        $recentBimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with('dosen')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', compact('stats', 'recentBimbingan'));
    }

    /**
     * Show list of bimbingan
     */
    public function bimbingan(): View
    {
        /** @var User $mahasiswa */
        $mahasiswa = \Illuminate\Support\Facades\Auth::user();

        $bimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with(['dosen', 'submissionFiles'])
            ->latest('created_at')
            ->paginate(15);

        return view('mahasiswa.bimbingan.index', compact('bimbingan'));
    }

    /**
     * Show detail bimbingan with submissions and comments
     */
    public function showBimbingan(Bimbingan $bimbingan): View
    {
        $this->authorize('view', $bimbingan);

        $submissions = $bimbingan->submissionFiles()
            ->where('mahasiswa_id', Auth::id())
            ->with(['comments.dosen', 'dosen'])
            ->latest('created_at')
            ->paginate(10);

        return view('mahasiswa.bimbingan.show', compact('bimbingan', 'submissions'));
    }

    /**
     * Show upload file form
     */
    public function uploadForm(Bimbingan $bimbingan): View
    {
        $this->authorize('view', $bimbingan);

        return view('mahasiswa.uploads.create', compact('bimbingan'));
    }

    /**
     * Store uploaded file
     */
    public function storeUpload(Request $request, Bimbingan $bimbingan): RedirectResponse
    {
        $this->authorize('view', $bimbingan);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'file_type' => 'required|in:draft,revision,final',
            'description' => 'nullable|string|max:1000',
        ]);

        // Store file
        $filePath = $request->file('file')->store('submissions', 'public');
        $fileSize = $request->file('file')->getSize();

        $submission = SubmissionFile::create([
            'bimbingan_id' => $bimbingan->id,
            'mahasiswa_id' => \Illuminate\Support\Facades\Auth::id(),
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $validated['file_type'],
            'file_size' => $fileSize,
            'description' => $validated['description'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('mahasiswa.bimbingan.show', $bimbingan->id)
            ->with('success', 'File berhasil diupload. Menunggu review dari dosen.');
    }

    /**
     * Show submission detail with comments
     */
    public function showSubmission(SubmissionFile $submission): View
    {
        $this->authorize('view', $submission);

        $comments = $submission->comments()
            ->with('dosen')
            ->orderBy('is_pinned', 'desc')
            ->latest('created_at')
            ->get();

        return view('mahasiswa.submissions.show', compact('submission', 'comments'));
    }

    /**
     * Show progress tracker
     */
    public function progress(): View
    {
        /** @var User $mahasiswa */
        $mahasiswa = Auth::user();

        $bimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with(['submissionFiles', 'dosen'])
            ->get();

        // Calculate progress
        $progressData = $bimbingan->map(function ($b) {
            $totalSubmissions = $b->submissionFiles()->count();
            $approvedSubmissions = $b->submissionFiles()->where('status', 'approved')->count();

            return [
                'bimbingan' => $b,
                'total_submissions' => $totalSubmissions,
                'approved_submissions' => $approvedSubmissions,
                'percentage' => $totalSubmissions > 0 ? ($approvedSubmissions / $totalSubmissions) * 100 : 0,
            ];
        });

        return view('mahasiswa.progress', compact('progressData'));
    }

    /**
     * Download archive of bimbingan history
     */
    public function downloadArchive(Bimbingan $bimbingan)
    {
        $this->authorize('view', $bimbingan);

        // Get all submissions for this bimbingan
        $submissions = $bimbingan->submissionFiles()
            ->where('mahasiswa_id', Auth::id())
            ->get();

        if ($submissions->isEmpty()) {
            return back()->with('error', 'Tidak ada file untuk didownload');
        }

        // Create zip file with all submissions
        $zip = new \ZipArchive();
        $zipFileName = "bimbingan_{$bimbingan->id}_" . now()->format('Y-m-d-His') . ".zip";
        $zipFilePath = storage_path("app/temp/{$zipFileName}");

        if (!file_exists(storage_path("app/temp"))) {
            mkdir(storage_path("app/temp"), 0755, true);
        }

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
            foreach ($submissions as $submission) {
                $filePath = storage_path("app/public/{$submission->file_path}");
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($submission->file_path));
                }
            }

            // Add metadata file
            $metadata = "Bimbingan: {$bimbingan->judul}\n";
            $metadata .= "Dosen: {$bimbingan->dosen->name}\n";
            $metadata .= "Status: {$bimbingan->status}\n";
            $metadata .= "Total File: " . count($submissions) . "\n\n";

            foreach ($submissions as $submission) {
                $metadata .= "File: {$submission->file_name}\n";
                $metadata .= "Tipe: {$submission->file_type}\n";
                $metadata .= "Status: {$submission->status}\n";
                $metadata .= "Upload: {$submission->submitted_at}\n\n";
            }

            $zip->addFromString('README.txt', $metadata);
            $zip->close();

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal membuat archive');
    }
}
