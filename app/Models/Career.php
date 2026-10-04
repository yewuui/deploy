<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Career extends Model
{
    use Concerns\ResolvesPublicFileUrl;
    use HasFactory;

    protected $fillable = [
        'program_id',
        'title',
        'short_description',
        'details',
        'tasks',
        'tools',
        'icon',
        'order_number',
        'is_active',
    ];

    protected $casts = [
        'tasks' => 'array',
        'tools' => 'array',
        'order_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function getIconUrlAttribute(): ?string
    {
        return self::resolvePublicFileUrl($this->icon);
    }
}
