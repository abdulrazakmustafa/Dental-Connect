<?php

namespace App\Modules\Clinic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicHour extends Model
{
    protected $fillable = ['clinic_id', 'day_of_week', 'opens_at', 'closes_at', 'is_closed'];

    protected function casts(): array
    {
        return ['is_closed' => 'boolean'];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
