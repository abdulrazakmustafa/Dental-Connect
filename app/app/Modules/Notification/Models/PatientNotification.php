<?php

namespace App\Modules\Notification\Models;

use App\Models\User;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Maps to the `notifications` table. Named PatientNotification (not
 * Notification) to avoid clashing with Illuminate\Notifications\Notification.
 */
class PatientNotification extends Model
{
    use HasPublicUlid;

    protected $table = 'notifications';

    public $timestamps = false;

    protected $fillable = ['user_id', 'type', 'title', 'body', 'related_type', 'related_id', 'read_at', 'created_at'];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
