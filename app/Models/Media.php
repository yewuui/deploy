<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use Concerns\ResolvesPublicFileUrl;
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'program_id',
        'platform',
        'handle',
        'description',
        'url',
        'image',
        'order_number',
        'is_active',
    ];

    protected $casts = [
        'order_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return self::resolvePublicFileUrl($this->image);
    }
}
