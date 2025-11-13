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
        $dosen = Auth::user();

        // Cek apakah dosen membimbing mahasiswa ini
        $mahasiswa = $dosen->mahasiswaBimbingan()
                          ->where('users.id', $id)
                          ->with('statusMahasiswa')
                          ->firstOrFail();

        // Ambil riwayat bimbingan
        $bimbingan = Bimbingan::where('mahasiswa_id', $id)
                             ->where('dosen_id', $dosen->id)
                             ->latest()
                             ->get();

        return view('dosen.mahasiswa.show', compact('mahasiswa', 'bimbingan'));
    }

    /**
     * Menampilkan detail bimbingan untuk review
     */
    public function reviewBimbingan($id)
    {
        $dosen = Auth::user();

        $bimbingan = Bimbingan::where('dosen_id', $dosen->id)
                             ->with('mahasiswa')
                             ->findOrFail($id);

        return view('dosen.bimbingan.review', compact('bimbingan'));
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

        return redirect()->route('dosen.dashboard')
                       ->with('success', 'Review berhasil disimpan!');
    }

    /**
     * Menyetujui mahasiswa layak sempro
     */
    public function approveLayakSempro(Request $request, $mahasiswaId)
    {
        $dosen = Auth::user();

        // Cek apakah dosen membimbing mahasiswa ini
        $isBimbingan = $dosen->mahasiswaBimbingan()
                            ->where('users.id', $mahasiswaId)
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
        $dosen = Auth::user();

        // Cek apakah dosen membimbing mahasiswa ini
        $isBimbingan = $dosen->mahasiswaBimbingan()
                            ->where('users.id', $mahasiswaId)
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
