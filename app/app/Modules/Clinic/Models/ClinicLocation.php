<?php

namespace App\Modules\Clinic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicLocation extends Model
{
    protected $fillable = [
        'clinic_id', 'label', 'address_line', 'region', 'city', 'area',
        'lat', 'lng', 'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'is_primary' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
