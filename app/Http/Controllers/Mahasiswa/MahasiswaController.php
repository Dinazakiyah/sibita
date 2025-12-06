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
    public function dashboard(): View
{
    $mahasiswa = Auth::user();

    $status = $mahasiswa->statusMahasiswa()->firstOrCreate(
        ['mahasiswa_id' => $mahasiswa->id],
        [
            'layak_sempro' => false,
            'layak_sidang' => false,
            'fase_aktif' => 'sempro',
        ]
    );

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

    public function bimbingan(): View
    {
        $mahasiswa = \Illuminate\Support\Facades\Auth::user();

        $bimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with(['dosen', 'submissionFiles'])
            ->latest('created_at')
            ->paginate(15);

        return view('Mahasiswa.Bimbingan.index', compact('bimbingan'));
    }

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

    public function uploadForm(Bimbingan $bimbingan): View
    {
        $this->authorize('view', $bimbingan);

        return view('Mahasiswa.uploads.create', compact('bimbingan'));
    }

    public function storeUpload(Request $request, Bimbingan $bimbingan): RedirectResponse
    {
        $this->authorize('view', $bimbingan);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,odt|max:10240',
            'file_type' => 'required|in:draft,revision,final',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $filename = time() . '_' . $request->file('file')->getClientOriginalName();
            $file = $request->file('file');
            $file->move(public_path('storage/bimbingan'), $filename);
            $filePath = 'bimbingan/' . $filename;
            $fileSize = $file->getSize();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal menyimpan file. Mohon cek konfigurasi server dan permission folder storage.']);
        }

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

    public function progress(): View
    {
        $mahasiswa = Auth::user();

        $bimbingan = $mahasiswa->bimbinganAsMahasiswa()
            ->with(['submissionFiles', 'dosen'])
            ->get();

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

    public function downloadArchive(Bimbingan $bimbingan)
    {
        $this->authorize('view', $bimbingan);

        $submissions = $bimbingan->submissionFiles()
            ->where('mahasiswa_id', Auth::id())
            ->get();

        if ($submissions->isEmpty()) {
            return back()->with('error', 'Tidak ada file untuk didownload');
        }

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

    $status = $mahasiswa->statusMahasiswa;
    $faseAktif = $status->fase_aktif;

    $dosenPembimbing = $mahasiswa->dosenPembimbing()->get();

    return view('Mahasiswa.Bimbingan.mahasiswa upload bimbingan', compact(
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
            'file' => 'required|mimes:pdf,doc,docx,odt|max:10240',
        ]);

        $mahasiswa = Auth::user();

        $filename = time() . '_' . $request->file('file')->getClientOriginalName();
        $file = $request->file('file');
        $file->move(public_path('storage/bimbingan'), $filename);
        $filePath = 'bimbingan/' . $filename;

        Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $request->dosen_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'fase' => in_array($mahasiswa->statusMahasiswa->fase_aktif, ['sempro','sidang']) ? $mahasiswa->statusMahasiswa->fase_aktif : 'sempro',
        ]);

        return redirect()->route('Mahasiswa.Bimbingan.mahasiswa upload bimbingan')
            ->with('success', 'Bimbingan baru berhasil dibuat dan diupload!');
    }


}
