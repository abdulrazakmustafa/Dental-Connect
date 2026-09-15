<?php

namespace App\Modules\Notification\Services;

use App\Models\User;
use App\Modules\Notification\Models\PatientNotification;
use Illuminate\Database\Eloquent\Model;

/**
 * In-app notifications (PRD §7.7): the minimum reliable channel. Email/SMS/
 * WhatsApp adapters are queued jobs layered on top of this later — this
 * service only writes the in-app record, and never blocks the caller's own
 * critical transaction (call it after commit).
 */
class NotificationService
{
    public function notify(User $user, string $type, string $title, ?string $body = null, ?Model $related = null): PatientNotification
    {
        return PatientNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'related_type' => $related ? $related::class : null,
            'related_id' => $related?->getKey(),
            'read_at' => null,
            'created_at' => now(),
        ]);
    }
}
