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

        $status = $mahasiswa->statusMahasiswa;

        $dosenPembimbing = $mahasiswa->dosenPembimbing()->get();

        $stats = [
            'total_bimbingan' => $mahasiswa->bimbinganAsMahasiswa()->count(),
            'pending_submissions' => SubmissionFile::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'submitted')
                ->count(),
            'approved_submissions' => SubmissionFile::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'approved')
                ->count(),
            'status_mahasiswa' => $status,
        ];

        $recentBimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with('dosen')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('Mahasiswa.dashboard', compact('stats', 'recentBimbingan', 'status', 'dosenPembimbing'));
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

        return view('Mahasiswa.Bimbingan.index', compact('bimbingan'));
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

        return view('Mahasiswa.Bimbingan.show', compact('bimbingan', 'submissions'));
    }

    /**
     * Show upload file form
     */
    public function uploadForm(Bimbingan $bimbingan): View
    {
        $this->authorize('view', $bimbingan);

        return view('Mahasiswa.uploads.create', compact('bimbingan'));
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
        try {
            $filePath = $request->file('file')->store('submissions', 'public');
            $fileSize = $request->file('file')->getSize();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal menyimpan file. Mohon cek konfigurasi server dan permission folder storage.']);
        }

        // Verify storage link exists
        if (!file_exists(public_path('storage'))) {
            return back()->withErrors(['file' => 'Storage link belum dibuat. Jalankan perintah: php artisan storage:link']);
        }

        // Save submission record with dosen_id as null initially
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
            'dosen_id' => null,
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

        return view('Mahasiswa.submissions.show', compact('submission', 'comments'));
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

        return view('Mahasiswa.progress', compact('progressData'));
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

    public function createBimbingan()
{
    $mahasiswa = Auth::user();

    // Ambil status mahasiswa (fase aktif: sempro/sidang)
    $status = $mahasiswa->statusMahasiswa;
    $faseAktif = $status->fase_aktif;

    // Ambil dosen pembimbing mahasiswa (pembimbing 1 & 2)
    $dosenPembimbing = $mahasiswa->dosenPembimbing()->get();

    return view('Mahasiswa.Bimbingan.create', compact(
        'dosenPembimbing',
        'faseAktif',
        'status'
    ));
}

public function storeBimbingan(Request $request)
{
    $request->validate([
        'dosen_id' => 'required|exists:users,id',
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'file' => 'required|mimes:pdf,doc,docx|max:5120', // 5MB
    ]);

    $mahasiswa = Auth::user();

    // Upload file
    $filePath = $request->file('file')->store(
        'bimbingan/' . $mahasiswa->id,
        'public'
    );

    // Simpan ke database
    Bimbingan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'dosen_id' => $request->dosen_id,
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'file_path' => $filePath,
        'fase' => $mahasiswa->statusMahasiswa->fase_aktif,  // sempro atau sidang
    ]);

    return redirect()->route('mahasiswa.bimbingan')
        ->with('success', 'Bimbingan baru berhasil dibuat dan diupload!');
}


}
