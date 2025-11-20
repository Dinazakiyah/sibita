namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use App\Models\Bimbingan;
use App\Models\SubmissionFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodController extends Controller
{
    /**
     * List semua periode bimbingan
     */
    public function index(): View
    {
        $periods = SchedulePeriod::orderBy('start_date', 'desc')
                    ->paginate(15);

        return view('Admin.periods.index', compact('periods'));
    }

    /**
     * Form membuat periode baru
     */
    public function create()
    {
        return view('Admin.periods.create');
    }

    /**
     * Simpan periode baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:end_date',
            'seminar_start_date' => 'nullable|date',
            'seminar_end_date' => 'nullable|date|after:seminar_start_date',
            'sidang_start_date' => 'nullable|date',
            'sidang_end_date' => 'nullable|date|after:sidang_start_date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            SchedulePeriod::create($validated);
        });

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode bimbingan berhasil dibuat');
    }

    /**
     * Laporan aktivitas bimbingan
     */
    public function reports(): View
    {
        $period = SchedulePeriod::where('is_active', true)->first()
                    ?? SchedulePeriod::latest()->first();

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

        return view('admin.reports.index', compact(
            'period',
            'bimbinganStats',
            'submissionStats'
        ));
    }
}
