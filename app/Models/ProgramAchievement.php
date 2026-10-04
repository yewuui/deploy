<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramAchievement extends Model
{
    use Concerns\ResolvesPublicFileUrl;
    use HasFactory;

    protected $table = 'program_achievements';

    protected $fillable = [
        'program_id',
        'title',
        'name',
        'date_label',
        'category',
        'level',
        'description',
        'image',
        'order_number',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order_number' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return self::resolvePublicFileUrl($this->image);
    }
}
