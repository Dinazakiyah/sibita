<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\StatusMahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Mahasiswa mengelola bimbingan
 */
class MahasiswaBimbinganController extends Controller
{
    /**
     * Menampilkan form upload bimbingan
     */
    public function create()
    {
        $mahasiswa = Auth::user();

        // Ambil status mahasiswa
        $status = StatusMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id]
        );

        // Tentukan fase bimbingan saat ini
        $faseAktif = $status->layak_sempro ? 'sidang' : 'sempro';

        // Ambil dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosenPembimbing;

        return view('Mahasiswa.uploads.create', compact(
            'faseAktif',
            'dosenPembimbing',
            'status'
        ));
    }

    /**
     * Menyimpan bimbingan baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        $mahasiswa = Auth::user();

        // Validasi bahwa dosen adalah pembimbing yang ditugaskan
        $isAssigned = $mahasiswa->dosenPembimbing()->where('users.id', $validated['dosen_id'])->exists();
        if (!$isAssigned) {
            return back()->withErrors(['dosen_id' => 'Dosen yang dipilih bukan pembimbing Anda yang ditugaskan.']);
        }

        // Tentukan fase
        $status = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)->first();
        $fase = $status && $status->layak_sempro ? 'sidang' : 'sempro';

        // Upload file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('bimbingan', $fileName, 'public');

        // Simpan ke database
        Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $validated['dosen_id'],
            'fase' => $fase,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.dashboard')
                       ->with('success', 'Bimbingan berhasil diupload! Menunggu review dosen.');
    }

    /**
     * Menampilkan detail bimbingan
     */
    public function show($id)
    {
        $bimbingan = Bimbingan::with('dosen')
                             ->where('mahasiswa_id', Auth::id())
                             ->findOrFail($id);

        return view('Mahasiswa.Bimbingan.show', compact('bimbingan'));
    }

    /**
     * Download file bimbingan
     */
    public function download($id)
    {
        $bimbingan = Bimbingan::where('mahasiswa_id', Auth::id())
                             ->findOrFail($id);

        // Cek apakah file ada
        if (!Storage::disk('public')->exists($bimbingan->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        // Ambil path fisik file pada disk 'public' dan gunakan response()->download
        $path = Storage::disk('public')->path($bimbingan->file_path);
        $filename = basename($bimbingan->file_path);

        return response()->download($path, $filename);
    }

    /**
     * Menampilkan riwayat bimbingan dalam format export
     */
    public function exportHistory()
    {
        $mahasiswa = Auth::user();
        $bimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
                             ->with('dosen')
                             ->orderBy('created_at')
                             ->get();

        $status = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)->first();

        return view('Mahasiswa.Bimbingan.riwayat bimbingan', compact('bimbingan', 'mahasiswa', 'status'));
    }
}
