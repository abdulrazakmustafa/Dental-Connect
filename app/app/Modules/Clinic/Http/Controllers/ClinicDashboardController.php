<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Marketplace\Models\Rfq;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $clinic = $request->user()->ownedClinics()->first()
            ?? $request->user()->clinicStaffMemberships()->with('clinic')->first()?->clinic;

        abort_if(! $clinic, 404, 'No clinic is associated with this account yet.');

        $today = today();
        $monthStart = today()->startOfMonth();

        $completedOrNoShowLast30Days = Appointment::where('clinic_id', $clinic->id)
            ->whereIn('status', [Appointment::STATUS_COMPLETED, Appointment::STATUS_NO_SHOW])
            ->where('preferred_date', '>=', now()->subDays(30))
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $completed = $completedOrNoShowLast30Days->get(Appointment::STATUS_COMPLETED, 0);
        $noShow = $completedOrNoShowLast30Days->get(Appointment::STATUS_NO_SHOW, 0);
        $completionRate = ($completed + $noShow) > 0 ? round($completed / ($completed + $noShow) * 100) : null;

        $recentAppointments = Appointment::where('clinic_id', $clinic->id)
            ->with('clinicPatient:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('clinic.dashboard', [
            'clinic' => $clinic,
            'todaysAppointments' => Appointment::where('clinic_id', $clinic->id)->whereDate('preferred_date', $today)->count(),
            'pendingRequests' => Appointment::where('clinic_id', $clinic->id)->where('status', Appointment::STATUS_REQUESTED)->count(),
            'totalPatients' => $clinic->patients()->count(),
            'newPatientsThisMonth' => $clinic->patients()->where('created_at', '>=', $monthStart)->count(),
            'completionRate' => $completionRate,
            'marketplaceEnquiries' => Rfq::where('clinic_id', $clinic->id)->count(),
            'recentAppointments' => $recentAppointments,
        ]);
    }
}
