<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\StatusMahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Dosen mengelola bimbingan
 */
class DosenBimbinganController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa bimbingan
     */
    public function index()
    {
        /** @var User $dosen */
        $dosen = Auth::user();

        // Ambil mahasiswa yang dibimbing
        $mahasiswa = $dosen->mahasiswaBimbingan()
                          ->with('statusMahasiswa')
                          ->get();

        return view('dosen.mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Menampilkan detail mahasiswa dan riwayat bimbingan
     */
    public function showMahasiswa($id)
    {
        /** @var User $dosen */
        $dosen = Auth::user();

        // Cek apakah dosen membimbing mahasiswa ini
        $mahasiswa = $dosen->mahasiswaBimbingan()
                          ->where('id', $id)
                          ->with('statusMahasiswa')
                          ->firstOrFail();

        // Ambil riwayat bimbingan
        $bimbingan = Bimbingan::where('mahasiswa_id', $id)
                             ->where('dosen_id', $dosen->id)
                             ->latest()
                             ->get();

        return view('dosen.mahasiswa.dosen lihat detail mahasiswa', compact('mahasiswa', 'bimbingan'));
    }

    /**
     * Menampilkan detail bimbingan untuk review
     */
    public function reviewBimbingan($id)
    {
        $dosen = Auth::user();

        $bimbingan = Bimbingan::where('dosen_id', $dosen->id)
                             ->with(['mahasiswa', 'submissionFiles.comments.dosen'])
                             ->findOrFail($id);

        return view('dosen.Bimbingan.dosen review bimbingan', compact('bimbingan'));
    }

    /**
     * Store comment on a submission file by dosen
     */
    public function commentOnSubmission(Request $request, $submissionId)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $dosen = Auth::user();

        $submission = \App\Models\SubmissionFile::findOrFail($submissionId);

        // Check if dosen is allowed to comment on this submission
        $bimbingan = $submission->bimbingan;
        if ($bimbingan->dosen_id !== $dosen->id) {
            return back()->with('error', 'Anda tidak berhak mengomentari file ini.');
        }

        // Create comment
        \App\Models\Comment::create([
            'submission_file_id' => $submission->id,
            'dosen_id' => $dosen->id,
            'comment' => $validated['comment'],
            'status' => null, // or provide a status if needed
            'is_pinned' => false,
            'priority' => 0,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Menyimpan review bimbingan
     */
    public function submitReview(Request $request, $id)
    {
        $validated = $request->validate([
            'komentar' => 'required|string',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:revisi,approved',
        ]);

        $dosen = Auth::user();

        $bimbingan = Bimbingan::where('dosen_id', $dosen->id)
                             ->findOrFail($id);

        // Update bimbingan
        $bimbingan->update([
            'komentar_dosen' => $validated['komentar'],
            'percentage' => $validated['percentage'],
            'status' => $validated['status'],
            'tanggal_revisi' => now(),
        ]);

        // Update all related submission files' status accordingly
        $submissionStatus = $validated['status'] === 'approved' ? 'approved' : 'revision_needed';

        $bimbingan->submissionFiles()->update([
            'status' => $submissionStatus,
            'reviewed_at' => now(),
            'dosen_id' => $dosen->id,
        ]);

        return redirect()->route('dosen.dashboard')
                       ->with('success', 'Review berhasil disimpan!');
    }

    /**
     * Menyetujui mahasiswa layak sempro
     */
    public function approveLayakSempro(Request $request, $mahasiswaId)
    {
        /** @var User $dosen */
        $dosen = Auth::user();

        // Cek apakah dosen membimbing mahasiswa ini
        $isBimbingan = $dosen->mahasiswaBimbingan()
                            ->where('id', $mahasiswaId)
                            ->exists();

        if (!$isBimbingan) {
            return back()->with('error', 'Anda tidak membimbing mahasiswa ini');
        }

        // Update status mahasiswa
        $status = StatusMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $mahasiswaId]
        );

        $status->update([
            'layak_sempro' => true,
            'tanggal_layak_sempro' => now(),
            'approved_by_dosen' => $dosen->id,
        ]);

        return back()->with('success', 'Mahasiswa dinyatakan layak sempro!');
    }

    /**
     * Menyetujui mahasiswa layak sidang
     */
    public function approveLayakSidang(Request $request, $mahasiswaId)
    {
        /** @var User $dosen */
        $dosen = Auth::user();

        // Cek apakah dosen membimbing mahasiswa ini
        $isBimbingan = $dosen->mahasiswaBimbingan()
                            ->where('id', $mahasiswaId)
                            ->exists();

        if (!$isBimbingan) {
            return back()->with('error', 'Anda tidak membimbing mahasiswa ini');
        }

        // Cek apakah sudah layak sempro
        $status = StatusMahasiswa::where('mahasiswa_id', $mahasiswaId)->first();

        if (!$status || !$status->layak_sempro) {
            return back()->with('error', 'Mahasiswa harus layak sempro terlebih dahulu');
        }

        // Update status
        $status->update([
            'layak_sidang' => true,
            'tanggal_layak_sidang' => now(),
            'approved_by_dosen' => $dosen->id,
        ]);

        return back()->with('success', 'Mahasiswa dinyatakan layak sidang!');
    }
}
