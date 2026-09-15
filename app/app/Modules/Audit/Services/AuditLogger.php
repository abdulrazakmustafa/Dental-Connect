<?php

namespace App\Modules\Audit\Services;

use App\Models\User;
use App\Modules\Audit\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Central write path for privileged-action audit entries (PRD §17).
 * Never pass passwords, tokens or full sensitive documents in $metadata.
 */
class AuditLogger
{
    public function log(
        string $action,
        ?Model $entity,
        ?User $actor = null,
        ?array $before = null,
        ?array $after = null,
        ?array $metadata = null,
    ): AuditLog {
        return AuditLog::create([
            'actor_user_id' => $actor?->id ?? auth()->id(),
            'action' => $action,
            'entity_type' => $entity ? $entity::class : null,
            'entity_id' => $entity?->getKey(),
            'before' => $before,
            'after' => $after,
            'metadata' => $metadata,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}
