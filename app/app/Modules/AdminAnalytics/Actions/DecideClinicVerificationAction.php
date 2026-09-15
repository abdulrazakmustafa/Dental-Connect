<?php

namespace App\Modules\AdminAnalytics\Actions;

use App\Models\User;
use App\Modules\Audit\Services\AuditLogger;
use App\Modules\Clinic\Models\Clinic;
use Illuminate\Support\Facades\DB;

/**
 * Admin moderation decision on a clinic verification submission (PRD §26
 * "Admin moderation" steps 26-32). The status transition + audit entry
 * commit together; the resulting notification is queued after commit.
 */
class DecideClinicVerificationAction
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function execute(Clinic $clinic, User $admin, string $decision, ?string $note = null): Clinic
    {
        $map = [
            'approve' => Clinic::STATUS_APPROVED,
            'reject' => Clinic::STATUS_REJECTED,
            'request_changes' => Clinic::STATUS_CHANGES_REQUESTED,
            'suspend' => Clinic::STATUS_SUSPENDED,
        ];

        abort_unless(isset($map[$decision]), 422, 'Unknown verification decision.');

        $before = ['verification_status' => $clinic->verification_status, 'is_active' => $clinic->is_active];

        DB::transaction(function () use ($clinic, $admin, $map, $decision, $note) {
            $clinic->update([
                'verification_status' => $map[$decision],
                'verified_at' => $map[$decision] === Clinic::STATUS_APPROVED ? now() : $clinic->verified_at,
                'is_active' => $map[$decision] === Clinic::STATUS_APPROVED,
            ]);

            $submission = $clinic->verificationSubmissions()->latest()->first();
            $submission?->update([
                'status' => $map[$decision],
                'reviewer_note' => $note,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            $this->audit->log(
                action: "clinic.{$decision}",
                entity: $clinic,
                actor: $admin,
                before: $before,
                after: ['verification_status' => $clinic->verification_status, 'is_active' => $clinic->is_active],
                metadata: $note ? ['note' => $note] : null,
            );
        });

        // TODO(Notification module): notify the clinic owner after commit.

        return $clinic->refresh();
    }
}
