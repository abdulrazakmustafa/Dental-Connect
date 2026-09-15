<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use App\Modules\Shared\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Maps to the `files` table. Named FileAsset (not File) to avoid clashing
 * with PHP's SPL File classes / Laravel's Illuminate\Http\File.
 */
class FileAsset extends Model
{
    use HasPublicUlid;

    protected $table = 'files';

    protected $fillable = [
        'owner_type', 'owner_id', 'category', 'disk', 'path', 'original_name',
        'mime_type', 'size_bytes', 'visibility', 'uploaded_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }
}
