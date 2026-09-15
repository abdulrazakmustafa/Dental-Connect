<?php

namespace App\Modules\TrustSupport\Models;

use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\Shared\Concerns\HasPublicUlid;
use App\Modules\TrustSupport\Policies\ReviewPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(ReviewPolicy::class)]
class Review extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'clinic_id', 'clinic_patient_id', 'appointment_id', 'rating', 'comment', 'moderation_status',
    ];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function clinicPatient(): BelongsTo
    {
        return $this->belongsTo(ClinicPatient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
