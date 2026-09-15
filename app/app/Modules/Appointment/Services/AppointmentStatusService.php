<?php

namespace App\Modules\Appointment\Services;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Appointment\Models\AppointmentStatusHistory;
use App\Modules\Notification\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Every status change is validated against Appointment::TRANSITIONS and
 * recorded in the immutable appointment_status_history trail (PRD §7.4/§26).
 * The transaction commits before the in-app notification is written
 * (REL-003/004) — a notification failure never rolls back the status change.
 */
class AppointmentStatusService
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function transition(Appointment $appointment, string $to, ?User $actor, ?string $note = null): Appointment
    {
        if (! $appointment->canTransitionTo($to)) {
            throw ValidationException::withMessages([
                'status' => "Cannot move an appointment from {$appointment->status} to {$to}.",
            ]);
        }

        DB::transaction(function () use ($appointment, $to, $actor, $note) {
            $from = $appointment->status;

            $timestampColumn = match ($to) {
                Appointment::STATUS_CONFIRMED => 'confirmed_at',
                Appointment::STATUS_COMPLETED => 'completed_at',
                Appointment::STATUS_CANCELLED => 'cancelled_at',
                default => null,
            };

            $appointment->status = $to;
            if ($timestampColumn) {
                $appointment->{$timestampColumn} = now();
            }
            $appointment->save();

            AppointmentStatusHistory::create([
                'appointment_id' => $appointment->id,
                'from_status' => $from,
                'to_status' => $to,
                'changed_by' => $actor?->id,
                'note' => $note,
                'created_at' => now(),
            ]);
        });

        $this->notifyPatient($appointment->fresh(['clinic', 'clinicPatient.user']), $to);

        return $appointment->refresh();
    }

    private function notifyPatient(Appointment $appointment, string $to): void
    {
        $patientUser = $appointment->clinicPatient->user;
        if (! $patientUser) {
            return;
        }

        $date = $appointment->preferred_date->format('j M');
        $message = match ($to) {
            Appointment::STATUS_CONFIRMED => "Your appointment for {$date} is confirmed.",
            Appointment::STATUS_RESCHEDULE_PROPOSED => "{$appointment->clinic->name} proposed a new time for your {$date} appointment.",
            Appointment::STATUS_DECLINED => "Your appointment request for {$date} was declined.",
            Appointment::STATUS_CANCELLED => "Your appointment for {$date} was cancelled.",
            Appointment::STATUS_COMPLETED => "Your appointment on {$date} is marked completed. Leave a review?",
            default => "Your appointment for {$date} was updated.",
        };

        $this->notifications->notify(
            $patientUser,
            'appointment.'.$to,
            $appointment->clinic->name,
            $message,
            $appointment,
        );
    }
}
