<?php

namespace App\Modules\Clinic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicBlackoutDate extends Model
{
    protected $fillable = ['clinic_id', 'dentist_id', 'date', 'reason'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }
}
