<?php

namespace App\Modules\Clinic\Models;

use App\Models\User;
use App\Modules\AdminAnalytics\Models\VerificationSubmission;
use App\Modules\Clinic\Policies\ClinicPolicy;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\TrustSupport\Models\Review;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[UsePolicy(ClinicPolicy::class)]
#[UseFactory(\Database\Factories\ClinicFactory::class)]
class Clinic extends Model
{
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'name', 'slug', 'description', 'email', 'phone', 'website',
        'logo_path', 'cover_image_path', 'owner_user_id', 'primary_region_id',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_CHANGES_REQUESTED = 'changes_requested';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_SUSPENDED = 'suspended';

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ClinicLocation::class);
    }

    public function primaryLocation(): HasOne
    {
        return $this->hasOne(ClinicLocation::class)->where('is_primary', true);
    }

    public function dentists(): HasMany
    {
        return $this->hasMany(Dentist::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'clinic_services')->withPivot('price');
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'clinic_specialties');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(ClinicPatient::class);
    }

    public function verificationSubmissions(): MorphMany
    {
        return $this->morphMany(VerificationSubmission::class, 'verifiable', 'verifiable_type', 'verifiable_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === self::STATUS_APPROVED;
    }
}
