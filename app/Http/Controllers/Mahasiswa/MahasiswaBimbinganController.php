<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbinganController extends Controller
{
    public function create()
    {
        $dosens = User::where('role', 'dosen')->get();
        return view('Mahasiswa.Bimbingan.mahasiswa upload bimbingan', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id'   => 'required|exists:users,id',
            'jenis_file' => 'required|string',
            'file'       => 'required|file|mimes:pdf,doc,docx',
            'deskripsi'  => 'nullable|string',
        ]);

        $filePath = $request->file('file')->store('bimbingan', 'public');

        Bimbingan::create([
            'judul_bimbingan' =>  Auth::user()->name,
            'mahasiswa_id' => Auth::user()->id,   // ✔ sudah benar
            'dosen_id'     => $request->dosen_id,
            'jenis_file'   => $request->jenis_file,
            'file_path'    => $filePath,
            'deskripsi'    => $request->deskripsi,
            'status'       => 'pending',
        ]);

        return redirect()->route('mahasiswa.bimbingan.create')
            ->with('success', 'File bimbingan berhasil diupload.');
    }
}
