<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Agent;
use App\Models\AgentCountry;
use App\Models\AgentDocument;
use App\Models\Appointment;
use App\Models\Attendance;
use App\Models\Backup;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyProfile;
use App\Models\Complaint;
use App\Models\Country;
use App\Models\Delegation;
use App\Models\DelegationDocument;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Fingerprint;
use App\Models\FiscalPeriod;
use App\Models\Flight;
use App\Models\Holiday;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Leave;
use App\Models\ManpowerApproval;
use App\Models\Medical;
use App\Models\MedicalCentre;
use App\Models\MofaProcess;
use App\Models\PassportHolder;
use App\Models\PassportHolderDocument;
use App\Models\PassportReturn;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\PoliceClearance;
use App\Models\Position;
use App\Models\PostArrival;
use App\Models\ArrivalSupport;
use App\Models\Recruitment;
use App\Models\RecruitmentDocument;
use App\Models\RecruitmentStageHistory;
use App\Models\SalaryStructure;
use App\Models\Selection;
use App\Models\SelectionGrade;
use App\Models\Setting;
use App\Models\Training;
use App\Models\TrainingCentre;
use App\Models\TrainingCourse;
use App\Models\TravelAgency;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\UserCountry;
use App\Models\Visa;
use App\Models\VisaLot;
use App\Models\VisaReturn;
use App\Models\Visitor;
use App\Models\VisitorPass;
use App\Models\Voucher;
use App\Models\WorkPermit;
use App\Observers\AuditObserver;
use App\Services\BrandingService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Super Admin bypasses every Policy check (Phase 01 section 8).
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }

            return null;
        });

        // Append-only audit trail on every business model (not on the
        // audit log itself, which would recurse, nor on login history).
        $audited = [
            Account::class, Agent::class, AgentCountry::class, AgentDocument::class,
            Appointment::class, Attendance::class, Backup::class, Branch::class,
            Company::class, CompanyProfile::class, Complaint::class, Country::class,
            Delegation::class, DelegationDocument::class, Department::class,
            Designation::class, Employee::class, Fingerprint::class, FiscalPeriod::class,
            Flight::class, Holiday::class, JournalEntry::class, JournalEntryLine::class,
            Leave::class, ManpowerApproval::class, Medical::class, MedicalCentre::class,
            MofaProcess::class, PassportHolder::class, PassportHolderDocument::class,
            PassportReturn::class, Payroll::class, PayrollItem::class,
            PoliceClearance::class, Position::class, PostArrival::class,
            ArrivalSupport::class, Recruitment::class, RecruitmentDocument::class,
            RecruitmentStageHistory::class, SalaryStructure::class, Selection::class,
            SelectionGrade::class, Setting::class, Training::class,
            TrainingCentre::class, TrainingCourse::class, TravelAgency::class,
            User::class, UserBranch::class, UserCountry::class, Visa::class,
            VisaLot::class, VisaReturn::class, Visitor::class, VisitorPass::class,
            Voucher::class, WorkPermit::class,
        ];

        foreach ($audited as $model) {
            $model::observe(AuditObserver::class);
        }

        // Branding available in every view (layout, login, PDFs).
        View::composer('*', function ($view) {
            $view->with('branding', BrandingService::profile());
        });
    }
}
