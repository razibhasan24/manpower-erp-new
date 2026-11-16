<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

/**
 * Attendance (Phase 26): manual check in/out + biometric sync
 * (adapter interface only — see BiometricAttendanceAdapterInterface).
 */
class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendance) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $items = Attendance::with('employee')
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('date', $request->date))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('date')->paginate(20)->withQueryString();

        if ($request->wantsJson()) {
            return AttendanceResource::collection($items);
        }

        return view('hrm.attendance.index', compact('items'));
    }

    public function checkIn(Request $request)
    {
        $this->authorize('create', Attendance::class);
        $employee = Employee::findOrFail($request->validate(['employee_id' => ['required', 'exists:employees,id']])['employee_id']);

        $record = $this->attendance->checkIn($employee);

        return back()->with('success', "Checked in at {$record->check_in}.");
    }

    public function checkOut(Request $request)
    {
        $this->authorize('create', Attendance::class);
        $employee = Employee::findOrFail($request->validate(['employee_id' => ['required', 'exists:employees,id']])['employee_id']);

        $record = $this->attendance->checkOut($employee);

        return back()->with('success', "Checked out at {$record->check_out}.");
    }

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('create', Attendance::class);

        $record = Attendance::create($request->validated());

        return redirect()->route('hrm.attendance.index')->with('success', 'Attendance recorded.');
    }

    public function syncBiometric(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $data = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $result = $this->attendance->syncFromBiometric($data['from'], $data['to']);

        return back()->with('success', $result['connected']
            ? "Biometric sync complete: {$result['imported']} punches imported from {$result['device']}."
            : "No biometric device is configured ({$result['device']}). Attendance remains manual.");
    }

    public function monthlySummary(Request $request, Employee $employee)
    {
        $this->authorize('view', $employee);
        $month = $request->query('month', now()->format('Y-m'));

        return view('hrm.attendance.summary', [
            'employee' => $employee,
            'summary' => $this->attendance->monthlySummary($employee, $month),
        ]);
    }
}
