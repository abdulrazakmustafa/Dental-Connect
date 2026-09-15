<?php

namespace App\Modules\Clinic\Models;

use App\Modules\Clinic\Policies\DentistPolicy;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UsePolicy(DentistPolicy::class)]
#[UseFactory(\Database\Factories\DentistFactory::class)]
class Dentist extends Model
{
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'clinic_id', 'user_id', 'full_name', 'photo_path', 'bio',
        'license_number', 'status', 'sort_order',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'dentist_specialties');
    }
}
