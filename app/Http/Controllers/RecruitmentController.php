<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Recruitment\StoreRecruitmentRequest;
use App\Http\Requests\Recruitment\UpdateRecruitmentRequest;
use App\Models\Recruitment;
use App\Services\RecruitmentService;
use Illuminate\Http\Request;
use App\Models\RecruitmentDocument;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Recruitment web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to RecruitmentService, then respond.
 */
class RecruitmentController extends Controller
{
    public function __construct(protected RecruitmentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Recruitment::class);

        $recruitments = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status', 'current_stage', 'service_type', 'active']), $request->user());

        $filterBranches = \App\Models\Branch::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterCountries = \App\Models\Country::query()->visibleTo($request->user())->orderBy('name')->get();
        $filterAgents = \App\Models\Agent::query()->visibleTo($request->user())->orderBy('name')->get();
        $serviceType = $request->string('service_type')->toString();

        return view('recruitments.index', ['items' => $recruitments, 'filterBranches' => $filterBranches, 'filterCountries' => $filterCountries, 'filterAgents' => $filterAgents, 'serviceType' => $serviceType]);
    }

    /**
     * Read-only pipeline board. Shows the current stage for every visible
     * registration and the 15-stage progress indicator. No stage can be
     * changed from this screen — transitions stay in WorkflowService.
     */
    public function tracking(Request $request)
    {
        $this->authorize('viewAny', Recruitment::class);

        $items = Recruitment::query()
            ->visibleTo($request->user())
            ->with(['passportHolder', 'agent', 'country', 'company', 'position'])
            ->when($request->filled('service_type'), fn ($query) => $query->where('service_type', $request->string('service_type')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%'.$request->string('search').'%';
                $query->where('tracking_number', 'like', $term);
            })
            ->when($request->filled('current_stage'), fn ($query) => $query->where('current_stage', $request->string('current_stage')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('recruitments.tracking', [
            'items' => $items,
            'stages' => $request->input('service_type') === 'student' ? \App\Services\WorkflowService::STUDENT_STAGES : ($request->input('service_type') === 'hajj_umrah' ? \App\Services\WorkflowService::HAJJ_UMRAH_STAGES : \App\Services\WorkflowService::STAGES),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Recruitment::class);

        $agents = \App\Models\Agent::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $positions = \App\Models\Position::query()->visibleTo($request->user())->where('status', 'active')->orderBy('title')->get();
        $packages = \App\Models\ServicePackage::query()->where('is_active', true)->orderBy('name')->get();
        $universities = \App\Models\University::query()->where('is_active', true)->orderBy('name')->get();

        $nextPassportHolderId = (int) (\App\Models\PassportHolder::withTrashed()->max('id') ?? 0) + 1;

        return view('recruitments.create', compact('agents', 'branches', 'countries', 'companies', 'positions', 'packages', 'universities', 'nextPassportHolderId'));
    }

    public function store(StoreRecruitmentRequest $request)
    {
        $recruitment = $this->service->create($request->validated(), $request->user());

        return redirect()->route('recruitments.show', $recruitment)
            ->with('success', 'Recruitment created successfully.');
    }

    public function show(Recruitment $recruitment)
    {
        $this->authorize('view', $recruitment);
        $recruitment->load(['servicePackage', 'university', 'stageHistories.changedBy', 'documents']);

        return view('recruitments.show', [
            'recruitment' => $recruitment,
            'universities' => \App\Models\University::query()->where('is_active', true)->orderBy('name')->get(),
            'workflowStages' => \App\Services\WorkflowService::stagesFor($recruitment),
        ]);
    }

    public function completeStudentStage(Request $request, Recruitment $recruitment)
    {
        $this->authorize('update', $recruitment);
        abort_unless($recruitment->service_type === 'student', 404);

        $data = $request->validate([
            'stage' => ['required', 'in:university_management,offer_letter,admission'],
            'university_id' => ['required_if:stage,university_management', 'nullable', \Illuminate\Validation\Rule::exists('universities', 'id')->where('is_active', true)],
            'document' => ['required_if:stage,offer_letter,admission', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $current = [$recruitment->current_stage, $recruitment->current_status];
        $eligible = match ($data['stage']) {
            'university_management' => in_array($current, [['selection', 'approved'], ['university_management', 'pending'], ['university_management', 'completed']], true),
            'offer_letter' => in_array($current, [['university_management', 'completed'], ['offer_letter', 'pending'], ['offer_letter', 'issued']], true),
            'admission' => in_array($current, [['offer_letter', 'issued'], ['admission', 'pending'], ['admission', 'admitted']], true),
        };
        abort_unless($eligible, 422, 'Complete the previous student workflow stage first.');

        $this->service->completeStudentStage($recruitment, $data, $request->user());

        return redirect()->route('recruitments.show', $recruitment)->with('success', 'Student workflow stage completed.');
    }

    /** Download print-ready CV, barcode entry form, or candidate ID card. */
    public function downloadFormat(Recruitment $recruitment, string $format)
    {
        $this->authorize('view', $recruitment);

        $recruitment->load(['passportHolder', 'agent', 'branch', 'country', 'company', 'position', 'medical']);
        $holder = $recruitment->passportHolder;
        $photoData = null;
        if ($holder?->photo_path && Storage::disk('private')->exists($holder->photo_path)) {
            $photoData = 'data:'.(Storage::disk('private')->mimeType($holder->photo_path) ?: 'image/jpeg').';base64,'.base64_encode(Storage::disk('private')->get($holder->photo_path));
        }

        $view = match ($format) {
            'cv' => 'recruitments.downloads.cv',
            'entry-form' => 'recruitments.downloads.entry-form',
            'id-card' => 'recruitments.downloads.id-card',
            default => abort(404),
        };

        return Pdf::loadView($view, [
            'recruitment' => $recruitment,
            'holder' => $holder,
            'photoData' => $photoData,
            'barcodeSvg' => $this->code39Svg($recruitment->tracking_number),
        ])->setPaper($format === 'id-card' ? [0, 0, 340, 510] : 'a4')
            ->stream($recruitment->tracking_number.'-'.$format.'.pdf');
    }

    /** Build a standards based Code 39 barcode from the registration code. */
    private function code39Svg(string $value): string
    {
        $patterns = [
            '0'=>'nnnwwnwnn','1'=>'wnnwnnnnw','2'=>'nnwwnnnnw','3'=>'wnwwnnnnn','4'=>'nnnwwnnnw','5'=>'wnnwwnnnn','6'=>'nnwwwnnnn','7'=>'nnnwnnwnw','8'=>'wnnwnnwnn','9'=>'nnwwnnwnn',
            'A'=>'wnnnnwnnw','B'=>'nnwnnwnnw','C'=>'wnwnnwnnn','D'=>'nnnnwwnnw','E'=>'wnnnwwnnn','F'=>'nnwnwwnnn','G'=>'nnnnnwwnw','H'=>'wnnnnwwnn','I'=>'nnwnnwwnn','J'=>'nnnnwwwnn',
            'K'=>'wnnnnnnww','L'=>'nnwnnnnww','M'=>'wnwnnnnwn','N'=>'nnnnwnnww','O'=>'wnnnwnnwn','P'=>'nnwnwnnwn','Q'=>'nnnnnnwww','R'=>'wnnnnnwwn','S'=>'nnwnnnwwn','T'=>'nnnnwnwwn',
            'U'=>'wwnnnnnnw','V'=>'nwwnnnnnw','W'=>'wwwnnnnnn','X'=>'nwnnwnnnw','Y'=>'wwnnwnnnn','Z'=>'nwwnwnnnn','-'=>'nwnnnnwnw','.'=>'wwnnnnwnn',' '=>'nwwnnnwnn','$'=>'nwnwnwnnn','/'=>'nwnwnnnwn','+'=>'nwnnnwnwn','%'=>'nnnwnwnwn','*'=>'nwnnwnwnn',
        ];
        $encoded = '*'.strtoupper($value).'*';
        $x = 10; $bars = '';
        foreach (str_split($encoded) as $char) {
            $pattern = $patterns[$char] ?? $patterns['-'];
            for ($i = 0; $i < 9; $i++) {
                $width = $pattern[$i] === 'w' ? 3 : 1;
                if ($i % 2 === 0) $bars .= '<rect x="'.$x.'" y="0" width="'.$width.'" height="50"/>';
                $x += $width;
            }
            $x += 1;
        }
        return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$x.'" height="50" viewBox="0 0 '.$x.' 50">'.$bars.'</svg>';
    }

    public function edit(Request $request, Recruitment $recruitment)
    {
        $this->authorize('update', $recruitment);

        $agents = \App\Models\Agent::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $branches = \App\Models\Branch::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $countries = \App\Models\Country::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::query()->visibleTo($request->user())->where('status', 'active')->orderBy('name')->get();
        $positions = \App\Models\Position::query()->visibleTo($request->user())->where('status', 'active')->orderBy('title')->get();

        $recruitment->load('passportHolder');
        return view('recruitments.edit', compact('recruitment', 'agents', 'branches', 'countries', 'companies', 'positions'));
    }

    public function update(UpdateRecruitmentRequest $request, Recruitment $recruitment)
    {
        $this->service->update($recruitment, $request->validated(), $request->user());

        return redirect()->route('recruitments.show', $recruitment)
            ->with('success', 'Recruitment updated successfully.');
    }

    public function destroy(Recruitment $recruitment)
    {
        $this->authorize('delete', $recruitment);

        $this->service->delete($recruitment, request()->user());

        return redirect()->route('recruitments.index')
            ->with('success', 'Recruitment removed successfully.');
    }

    /**
     * Authorised download of a recruitment document. The file lives on
     * the private disk; there is no public URL for it.
     */
    public function downloadDocument(Recruitment $recruitment, RecruitmentDocument $document)
    {
        $this->authorize('downloadDocument', $recruitment);

        if ((int) $document->recruitment_id !== (int) $recruitment->id) {
            abort(404);
        }

        if (! Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'The requested file is no longer available.');
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name ?? basename($document->file_path)
        );
    }

    /**
     * Cancel a registration. The record is kept as history; only its
     * status changes to cancelled.
     */
    public function cancel(HttpRequest $request, Recruitment $recruitment)
    {
        $this->authorize('cancel', $recruitment);

        $validated = $request->validate(['remarks' => ['nullable', 'string']]);

        $this->service->cancel($recruitment, $validated['remarks'] ?? null, $request->user());

        return redirect()->route('recruitments.show', $recruitment)
            ->with('success', 'Registration cancelled. The record is kept as history.');
    }
}
