<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\ClinicBlackoutDate;
use App\Modules\Clinic\Models\ClinicHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Working hours + blackout dates (PRD §7.3 "basic availability, working
 * hours and blackout dates"). Phase 1 booking uses a fixed common time-slot
 * list rather than deriving slots from these hours — see
 * AppointmentController — but clinics can still publish and maintain them.
 */
class ClinicAvailabilityController extends Controller
{
    private const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function index(Request $request): View
    {
        $clinic = $this->currentClinic($request);

        $hours = ClinicHour::where('clinic_id', $clinic->id)->orderBy('day_of_week')->get()->keyBy('day_of_week');
        $blackoutDates = ClinicBlackoutDate::where('clinic_id', $clinic->id)->whereNull('dentist_id')->orderBy('date')->get();

        return view('clinic.availability.index', [
            'clinic' => $clinic,
            'days' => self::DAYS,
            'hours' => $hours,
            'blackoutDates' => $blackoutDates,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $clinic = $this->currentClinic($request);

        $data = $request->validate([
            'days' => ['array'],
            'days.*.closed' => ['nullable'],
            'days.*.opens_at' => ['nullable', 'date_format:H:i'],
            'days.*.closes_at' => ['nullable', 'date_format:H:i'],
        ]);

        foreach ($data['days'] ?? [] as $day => $row) {
            ClinicHour::updateOrCreate(
                ['clinic_id' => $clinic->id, 'day_of_week' => $day],
                [
                    'is_closed' => ! empty($row['closed']),
                    'opens_at' => empty($row['closed']) ? ($row['opens_at'] ?? null) : null,
                    'closes_at' => empty($row['closed']) ? ($row['closes_at'] ?? null) : null,
                ]
            );
        }

        return redirect()->route('clinic.availability.index')->with('status', 'Working hours updated.');
    }

    public function storeBlackout(Request $request): RedirectResponse
    {
        $clinic = $this->currentClinic($request);

        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:160'],
        ]);

        ClinicBlackoutDate::firstOrCreate(
            ['clinic_id' => $clinic->id, 'dentist_id' => null, 'date' => $data['date']],
            ['reason' => $data['reason'] ?? null]
        );

        return redirect()->route('clinic.availability.index')->with('status', 'Blackout date added.');
    }

    public function destroyBlackout(Request $request, ClinicBlackoutDate $blackoutDate): RedirectResponse
    {
        $clinic = $this->currentClinic($request);
        abort_unless($blackoutDate->clinic_id === $clinic->id, 403);

        $blackoutDate->delete();

        return redirect()->route('clinic.availability.index')->with('status', 'Blackout date removed.');
    }

    private function currentClinic(Request $request): Clinic
    {
        $clinic = $request->user()->ownedClinics()->first()
            ?? $request->user()->clinicStaffMemberships()->with('clinic')->first()?->clinic;

        abort_if(! $clinic, 404, 'No clinic is associated with this account yet.');

        return $clinic;
    }
}
