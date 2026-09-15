<?php

namespace App\Modules\Shared\Concerns;

use Illuminate\Support\Str;

/**
 * Every publicly exposed model has an internal auto-increment id (joins/FKs)
 * and a separate opaque ULID used in URLs/APIs. Never expose the internal id.
 */
trait HasPublicUlid
{
    public static function bootHasPublicUlid(): void
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}
