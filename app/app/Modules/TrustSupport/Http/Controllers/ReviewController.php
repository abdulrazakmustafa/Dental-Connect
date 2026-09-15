<?php

namespace App\Modules\TrustSupport\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\TrustSupport\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Review submission is linked to a specific completed appointment (PRD
 * §7.2/§13.1): one review per (appointment, clinic_patient) — enforced by
 * the DB unique constraint too.
 */
class ReviewController extends Controller
{
    public function create(Request $request, Appointment $appointment): View
    {
        abort_unless($appointment->clinicPatient->user_id === $request->user()->id, 403);
        abort_unless($appointment->status === Appointment::STATUS_COMPLETED, 404);

        if ($existing = Review::where('appointment_id', $appointment->id)->first()) {
            return view('patient.reviews.show', ['appointment' => $appointment, 'review' => $existing]);
        }

        return view('patient.reviews.create', ['appointment' => $appointment->load('clinic')]);
    }

    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless($appointment->clinicPatient->user_id === $request->user()->id, 403);
        abort_unless($appointment->status === Appointment::STATUS_COMPLETED, 404);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::firstOrCreate(
            ['appointment_id' => $appointment->id, 'clinic_patient_id' => $appointment->clinic_patient_id],
            [
                'clinic_id' => $appointment->clinic_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'moderation_status' => 'published',
            ]
        );

        return redirect()->route('patient.appointments.show', $appointment)->with('status', 'Thanks for your review!');
    }
}
