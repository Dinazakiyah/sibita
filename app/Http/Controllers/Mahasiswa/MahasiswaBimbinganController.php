<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;

class MahasiswaBimbinganController extends Controller
{
    public function create()
    {
        Log::info('MahasiswaBimbinganController::create called by user: ' . (Auth::check() ? Auth::id() : 'guest'));
        $dosens = User::where('role', 'dosen')->get();
        return view('Mahasiswa.Bimbingan.mahasiswa upload bimbingan', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id'   => 'required|exists:users,id',
            'fase' => 'required|string',
            // Allow PDF, Word documents and ODF (odt)
            'file'       => 'required|file|mimes:pdf,doc,docx,odt|max:10240', // max 10MB
            'judul'  => 'required|string',
            'deskripsi'  => 'nullable|string',
        ]);

        $filePath = $request->file('file')->store('bimbingan', 'public');

        Bimbingan::create([
            'judul' => $request->judul,
            'mahasiswa_id' => Auth::user()->id,   // ✔ sudah benar
            'dosen_id'     => $request->dosen_id,
            'fase'   => $request->fase,
            'file_path'    => $filePath,
            'deskripsi'    => $request->deskripsi,
            'status'       => 'pending',
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('mahasiswa.bimbingan.create')
            ->with('success', 'File bimbingan berhasil diupload.');
    }

    /**
     * Tampilkan halaman booking jadwal bimbingan
     */
    public function appointmentsIndex()
    {
        $dosens = User::where('role', 'dosen')->get();
        return view('Mahasiswa.Bimbingan.appointments', compact('dosens'));
    }

    /**
     * Booking jadwal bimbingan
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Cek apakah slot sudah dibooking
        $existingAppointment = Appointment::where('dosen_id', $request->dosen_id)
            ->where('scheduled_date', $request->scheduled_date)
            ->where('scheduled_time', $request->scheduled_time)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingAppointment) {
            return back()->withErrors(['slot' => 'Jadwal ini sudah dibooking. Silakan pilih jadwal lain.']);
        }

        Appointment::create([
            'mahasiswa_id' => Auth::id(),
            'dosen_id' => $request->dosen_id,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.appointments.index')
            ->with('success', 'Jadwal bimbingan berhasil dibooking. Menunggu persetujuan dosen.');
    }

    /**
     * Tampilkan riwayat booking mahasiswa
     */
    public function myAppointments()
    {
        $appointments = Appointment::where('mahasiswa_id', Auth::id())
            ->with('dosen')
            ->orderBy('scheduled_date', 'desc')
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return view('Mahasiswa.Bimbingan.my-appointments', compact('appointments'));
    }

    /**
     * Batalkan booking
     */
    public function cancelAppointment($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('mahasiswa_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('mahasiswa.appointments.my')
            ->with('success', 'Booking jadwal berhasil dibatalkan.');
    }
}
