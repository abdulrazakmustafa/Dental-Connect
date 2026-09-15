<?php

namespace App\Modules\AdminAnalytics\Models;

use App\Models\User;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VerificationSubmission extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'verifiable_type', 'verifiable_id', 'status', 'reviewer_note',
        'reviewed_by', 'submitted_at', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function verifiable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'verifiable_type', 'verifiable_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
