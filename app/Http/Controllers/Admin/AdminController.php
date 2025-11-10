<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StatusMahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk Admin mengelola data
 */
class AdminController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa
     */
    public function indexMahasiswa()
    {
        $mahasiswa = User::where('role', 'mahasiswa')
                        ->with('statusMahasiswa', 'dosenPembimbing')
                        ->latest()
                        ->paginate(20);

        return view('admin.mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Menampilkan form tambah mahasiswa
     */
    public function createMahasiswa()
    {
        $dosen = User::where('role', 'dosen')->get();
        return view('admin.mahasiswa.create', compact('dosen'));
    }

    /**
     * Menyimpan mahasiswa baru
     */
    public function storeMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'nim_nip' => 'required|string|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:6',
            'dosen_pembimbing_1' => 'required|exists:users,id',
            'dosen_pembimbing_2' => 'required|exists:users,id|different:dosen_pembimbing_1',
        ]);

        DB::beginTransaction();
        try {
            // Buat user mahasiswa
            $mahasiswa = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nim_nip' => $validated['nim_nip'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'mahasiswa',
            ]);

            // Assign dosen pembimbing
            $mahasiswa->dosenPembimbing()->attach([
                $validated['dosen_pembimbing_1'] => ['jenis_pembimbing' => 'pembimbing_1'],
                $validated['dosen_pembimbing_2'] => ['jenis_pembimbing' => 'pembimbing_2'],
            ]);

            // Buat status mahasiswa
            StatusMahasiswa::create([
                'mahasiswa_id' => $mahasiswa->id,
                'layak_sempro' => false,
                'layak_sidang' => false,
            ]);

            DB::commit();

            return redirect()->route('admin.mahasiswa.index')
                           ->with('success', 'Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Menampilkan daftar dosen
     */
    public function indexDosen()
    {
        $dosen = User::where('role', 'dosen')
                    ->withCount('mahasiswaBimbingan')
                    ->latest()
                    ->paginate(20);

        return view('admin.dosen.index', compact('dosen'));
    }

    /**
     * Menampilkan form tambah dosen
     */
    public function createDosen()
    {
        return view('admin.dosen.create');
    }

    /**
     * Menyimpan dosen baru
     */
    public function storeDosen(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'nim_nip' => 'required|string|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim_nip' => $validated['nim_nip'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'dosen',
        ]);

        return redirect()->route('admin.dosen.index')
                       ->with('success', 'Dosen berhasil ditambahkan!');
    }

    /**
     * Menampilkan laporan aktivitas
     */
    public function laporan()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $layakSempro = StatusMahasiswa::where('layak_sempro', true)->count();
        $layakSidang = StatusMahasiswa::where('layak_sidang', true)->count();

        // Ambil data per dosen
        $dosenStats = User::where('role', 'dosen')
                         ->withCount([
                             'mahasiswaBimbingan',
                             'bimbinganAsDosen'
                         ])
                         ->get();

        return view('admin.laporan', compact(
            'totalMahasiswa',
            'totalDosen',
            'layakSempro',
            'layakSidang',
            'dosenStats'
        ));
    }
}
