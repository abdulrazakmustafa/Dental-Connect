<?php

namespace App\Modules\Appointment\Services;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Appointment\Models\AppointmentStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Every status change is validated against Appointment::TRANSITIONS and
 * recorded in the immutable appointment_status_history trail (PRD §7.4/§26).
 * The transaction commits before any notification is queued (REL-003/004).
 */
class AppointmentStatusService
{
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

        // TODO(Notification module): dispatch a queued notification job here,
        // after commit, once the Notification module adapters are built.

        return $appointment->refresh();
    }
}
