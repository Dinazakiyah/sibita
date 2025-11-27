<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\StatusMahasiswa;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Dashboard
 * Menampilkan halaman utama sesuai role
 */
class DashboardController extends Controller
{
    /**
     * Dashboard utama - redirect sesuai role
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Redirect ke dashboard sesuai role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isDosen()) {
            return $this->dosenDashboard();
        } else {
            return $this->mahasiswaDashboard();
        }
    }

    /**
     * Dashboard Admin Prodi
     */
    private function adminDashboard()
    {
        // Hitung statistik
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalBimbingan = Bimbingan::count();
        $mahasiswaLayakSempro = StatusMahasiswa::where('layak_sempro', true)->count();
        $mahasiswaLayakSidang = StatusMahasiswa::where('layak_sidang', true)->count();

        // Ambil data mahasiswa dengan status
        $mahasiswa = User::where('role', 'mahasiswa')
                        ->with('statusMahasiswa')
                        ->latest()
                        ->take(10)
                        ->get();

        // Ambil aktivitas bimbingan terbaru
        $recentBimbingan = Bimbingan::with(['mahasiswa', 'dosen'])
                                    ->latest()
                                    ->take(10)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalBimbingan',
            'mahasiswaLayakSempro',
            'mahasiswaLayakSidang',
            'mahasiswa',
            'recentBimbingan'
        ));
    }

    /**
     * Dashboard Dosen Pembimbing
     */
    private function dosenDashboard()
    {
        /** @var User $dosen */
        $dosen = Auth::user();

        // Ambil mahasiswa yang dibimbing
        $mahasiswaBimbingan = $dosen->mahasiswaBimbingan()
                                   ->with('statusMahasiswa')
                                   ->get();

        // Hitung statistik
        $totalMahasiswa = $mahasiswaBimbingan->count();
        $pendingReview = Bimbingan::where('dosen_id', $dosen->id)
                                  ->where('status', 'pending')
                                  ->count();

        // Ambil bimbingan yang perlu direview
        $bimbinganPending = Bimbingan::where('dosen_id', $dosen->id)
                                     1

        // Aktivitas bimbingan terbaru
        $recentBimbingan = Bimbingan::where('dosen_id', $dosen->id)
                                    ->with('mahasiswa')
                                    ->latest()
                                    ->take(10)
                                    ->get();

        return view('dosen.dashboard', compact(
            'mahasiswaBimbingan',
            'totalMahasiswa',
            'pendingReview',
            'bimbinganPending',
            'recentBimbingan'
        ));
    }

    /**
     * Dashboard Mahasiswa
     */
    private function mahasiswaDashboard()
    {
        /** @var User $mahasiswa */
        $mahasiswa = Auth::user();

        // Ambil atau buat status mahasiswa
        $status = StatusMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'layak_sempro' => false,
                'layak_sidang' => false,
            ]
        );

        // Ambil dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosenPembimbing;

        // Hitung jumlah bimbingan
        $totalBimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa->id)->count();
        $bimbinganApproved = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
                                     ->where('status', 'approved')
                                     ->count();

        // Ambil riwayat bimbingan
        $riwayatBimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
                                     ->with('dosen')
                                     ->latest()
                                     ->get();

        return view('mahasiswa.dashboard', compact(
            'status',
            'dosenPembimbing',
            'totalBimbingan',
            'bimbinganApproved',
            'riwayatBimbingan'
        ));
    }
}
