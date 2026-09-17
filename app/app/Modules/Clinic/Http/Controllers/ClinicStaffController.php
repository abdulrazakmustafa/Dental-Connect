<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Actions\AddClinicStaffAction;
use App\Modules\Clinic\Http\Requests\ClinicStaffRequest;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\ClinicStaff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Clinic staff management (PRD §38) — owner-only. */
class ClinicStaffController extends Controller
{
    public function index(Request $request): View
    {
        $clinic = $this->ownedClinic($request);

        $staff = ClinicStaff::where('clinic_id', $clinic->id)->with('user')->get();

        return view('clinic.staff.index', ['clinic' => $clinic, 'staff' => $staff]);
    }

    public function store(ClinicStaffRequest $request, AddClinicStaffAction $action): RedirectResponse
    {
        $clinic = $this->ownedClinic($request);

        $result = $action->execute($clinic, $request->validated());

        return redirect()->route('clinic.staff.index')->with(
            'status',
            $result['temporaryPassword']
                ? "Staff added. Temporary password for {$result['user']->email}: {$result['temporaryPassword']} (share this with them directly; it will not be shown again)."
                : 'Staff added.'
        );
    }

    public function destroy(Request $request, ClinicStaff $clinicStaff): RedirectResponse
    {
        $clinic = $this->ownedClinic($request);
        abort_unless($clinicStaff->clinic_id === $clinic->id, 403);

        $userId = $clinicStaff->user_id;
        $clinicStaff->delete();

        if (! ClinicStaff::where('user_id', $userId)->exists()) {
            $clinicStaff->user?->syncRoles([]);
        }

        return redirect()->route('clinic.staff.index')->with('status', 'Staff member removed.');
    }

    private function ownedClinic(Request $request): Clinic
    {
        return $request->user()->ownedClinics()->firstOrFail();
    }
}
