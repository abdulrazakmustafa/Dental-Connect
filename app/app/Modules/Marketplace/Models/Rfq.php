<?php

namespace App\Modules\Marketplace\Models;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Marketplace\Policies\RfqPolicy;
use App\Modules\Shared\Concerns\HasPublicUlid;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(RfqPolicy::class)]
#[UseFactory(\Database\Factories\RfqFactory::class)]
class Rfq extends Model
{
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'clinic_id', 'supplier_id', 'product_id', 'requested_by',
        'quantity', 'message', 'status',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(RfqMessage::class);
    }
}
