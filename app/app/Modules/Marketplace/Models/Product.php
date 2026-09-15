<?php

namespace App\Modules\Marketplace\Models;

use App\Modules\Marketplace\Policies\ProductPolicy;
use App\Modules\Shared\Concerns\HasPublicUlid;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(ProductPolicy::class)]
#[UseFactory(\Database\Factories\ProductFactory::class)]
class Product extends Model
{
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'supplier_id', 'category_id', 'name', 'slug', 'brand', 'reference_code',
        'description', 'specifications', 'price', 'price_unit', 'price_visible',
        'status', 'moderation_status', 'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_visible' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function rfqs(): HasMany
    {
        return $this->hasMany(Rfq::class);
    }
}
