<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Marketplace\Models\Rfq;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'appointmentTrend' => $this->appointmentTrend($clinic->id),
            'statusBreakdown' => $this->statusBreakdown($clinic->id),
        ]);
    }

    /** Requested appointments per day, last 14 days — for the trend line chart. */
    private function appointmentTrend(int $clinicId): array
    {
        $start = now()->subDays(13)->startOfDay();

        $counts = Appointment::where('clinic_id', $clinicId)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('j M');
            $data[] = (int) ($counts[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function statusBreakdown(int $clinicId): array
    {
        $counts = Appointment::where('clinic_id', $clinicId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['requested', 'confirmed', 'completed', 'cancelled', 'declined', 'no_show'];

        return [
            'labels' => array_map(fn ($s) => ucfirst(str_replace('_', ' ', $s)), $statuses),
            'data' => array_map(fn ($s) => (int) ($counts[$s] ?? 0), $statuses),
        ];
    }
}
