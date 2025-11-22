<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StatusMahasiswa;
use App\Models\SchedulePeriod;
use App\Models\Bimbingan;
use App\Models\SubmissionFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard(): View
    {
        $stats = [
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_dosen' => User::where('role', 'dosen')->count(),
            'total_bimbingan' => Bimbingan::count(),
            'active_period' => SchedulePeriod::where('is_active', true)->first(),
        ];

        $recentBimbingan = Bimbingan::with(['mahasiswa', 'dosen'])
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('Admin.dashboard', compact('stats', 'recentBimbingan'));
    }

    /**
     * Menampilkan daftar mahasiswa
     */
    public function indexMahasiswa()
    {
        $mahasiswa = User::where('role', 'mahasiswa')
                        ->with('statusMahasiswa', 'dosenPembimbing')
                        ->latest()
                        ->paginate(20);

        return view('Admin.Mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Menampilkan form tambah mahasiswa
     */
    public function createMahasiswa()
    {
        $dosen = User::where('role', 'dosen')->get();
        return view('Admin.Mahasiswa.create', compact('dosen'));
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

        return view('Admin.dosen.index', compact('dosen'));
    }

    /**
     * Menampilkan form tambah dosen
     */
    public function createDosen()
    {
        return view('Admin.dosen.create');
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

        DB::beginTransaction();
        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nim_nip' => $validated['nim_nip'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'dosen',
            ]);

            DB::commit();

            return redirect()->route('admin.dosen.index')
                           ->with('success', 'Dosen berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
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

        return view('Admin.laporan', compact(
            'totalMahasiswa',
            'totalDosen',
            'layakSempro',
            'layakSidang',
            'dosenStats'
        ));
    }

    /**
     * Show schedule periods (ini itu fungsinya apa?)
     */
    public function periods(): View
    {
        $periods = SchedulePeriod::orderBy('start_date', 'desc')->paginate(15);
        return view('Admin.periods.index', compact('periods'));
    }

    /**
     * Show create period form
     */
    public function createPeriod()
    {
        return view('Admin.periods.create');
    }

    /**
     * Store new period
     */
    public function storePeriod()
    {
        $validated = request()->validate([
            'period_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:end_date',
            'seminar_start_date' => 'nullable|date|after:start_date',
            'seminar_end_date' => 'nullable|date|after:seminar_start_date',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            SchedulePeriod::create($validated);

            DB::commit();

            return redirect()->route('admin.periods')->with('success', 'Periode baru berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Show reports
     */
    public function reports(): View
    {
        $period = SchedulePeriod::where('is_active', true)->first();

        if (!$period) {
            $period = SchedulePeriod::latest('created_at')->first();
        }

        $bimbinganStats = [
            'total' => Bimbingan::count(),
            'pending' => Bimbingan::where('status', 'pending')->count(),
            'revisi' => Bimbingan::where('status', 'revisi')->count(),
            'approved' => Bimbingan::where('status', 'approved')->count(),
        ];

        $submissionStats = [
            'total' => SubmissionFile::count(),
            'submitted' => SubmissionFile::where('status', 'submitted')->count(),
            'reviewed' => SubmissionFile::where('status', 'reviewed')->count(),
            'approved' => SubmissionFile::where('status', 'approved')->count(),
            'rejected' => SubmissionFile::where('status', 'rejected')->count(),
        ];

        return view('Admin.laporan', compact('period', 'bimbinganStats', 'submissionStats'));
    }
}
