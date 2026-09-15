<?php

namespace App\Modules\Patient\Models;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Patient\Policies\ClinicPatientPolicy;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The clinic-owned patient relationship. NEVER query across clinics — every
 * lookup must be scoped by clinic_id (see App\Modules\Patient\Policies).
 * A single `users` login identity may own many ClinicPatient rows.
 */
#[UsePolicy(ClinicPatientPolicy::class)]
#[UseFactory(\Database\Factories\ClinicPatientFactory::class)]
class ClinicPatient extends Model
{
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'clinic_id', 'user_id', 'patient_number', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'phone', 'email', 'assigned_dentist_id',
        'status', 'internal_notes',
    ];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedDentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class, 'assigned_dentist_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
