<?php

namespace App\Modules\Appointment\Models;

use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Clinic\Models\Service;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\Appointment\Policies\AppointmentPolicy;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(AppointmentPolicy::class)]
#[UseFactory(\Database\Factories\AppointmentFactory::class)]
class Appointment extends Model
{
    use HasFactory, HasPublicUlid;

    public const STATUS_REQUESTED = 'requested';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_RESCHEDULE_PROPOSED = 'reschedule_proposed';

    public const STATUS_DECLINED = 'declined';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_NO_SHOW = 'no_show';

    /** Valid status transitions, enforced by AppointmentStatusTransitionService. */
    public const TRANSITIONS = [
        self::STATUS_REQUESTED => [self::STATUS_CONFIRMED, self::STATUS_RESCHEDULE_PROPOSED, self::STATUS_DECLINED, self::STATUS_CANCELLED],
        self::STATUS_RESCHEDULE_PROPOSED => [self::STATUS_CONFIRMED, self::STATUS_DECLINED, self::STATUS_CANCELLED],
        self::STATUS_CONFIRMED => [self::STATUS_COMPLETED, self::STATUS_NO_SHOW, self::STATUS_CANCELLED],
        self::STATUS_DECLINED => [],
        self::STATUS_CANCELLED => [],
        self::STATUS_COMPLETED => [],
        self::STATUS_NO_SHOW => [],
    ];

    protected $fillable = [
        'clinic_id', 'clinic_patient_id', 'dentist_id', 'service_id',
        'preferred_date', 'preferred_time', 'patient_note', 'clinic_note', 'status',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function clinicPatient(): BelongsTo
    {
        return $this->belongsTo(ClinicPatient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(AppointmentStatusHistory::class);
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, self::TRANSITIONS[$this->status] ?? [], true);
    }

    /** "10:30:00" -> "10:30 AM", for display only — the raw DB value stays HH:MM:SS. */
    public function formattedTime(): ?string
    {
        return $this->preferred_time
            ? \Carbon\Carbon::createFromFormat('H:i:s', $this->preferred_time)->format('g:i A')
            : null;
    }
}
