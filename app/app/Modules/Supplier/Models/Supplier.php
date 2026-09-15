<?php

namespace App\Modules\Supplier\Models;

use App\Models\User;
use App\Modules\AdminAnalytics\Models\VerificationSubmission;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Shared\Concerns\HasPublicUlid;
use App\Modules\Supplier\Policies\SupplierPolicy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[UsePolicy(SupplierPolicy::class)]
#[UseFactory(\Database\Factories\SupplierFactory::class)]
class Supplier extends Model
{
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'name', 'slug', 'description', 'email', 'phone', 'website',
        'logo_path', 'owner_user_id', 'region', 'city',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function verificationSubmissions(): MorphMany
    {
        return $this->morphMany(VerificationSubmission::class, 'verifiable', 'verifiable_type', 'verifiable_id');
    }

    public function rfqs(): HasMany
    {
        return $this->hasMany(\App\Modules\Marketplace\Models\Rfq::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'approved';
    }
}
