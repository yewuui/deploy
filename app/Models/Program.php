<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use Concerns\ResolvesPublicFileUrl;
    use HasFactory;

    protected $fillable = [
        'program_keahlian_id',
        'name',
        'short_name',
        'slug',
        'description',
        'logo',
        'hero_title',
        'hero_text',
        'highlights',
        'is_active',
    ];

    protected $casts = [
        'highlights' => 'array',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function programKeahlian(): BelongsTo
    {
        return $this->belongsTo(ProgramKeahlian::class);
    }

    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function careers(): HasMany
    {
        return $this->hasMany(Career::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(ProgramAchievement::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return self::resolvePublicFileUrl($this->logo);
    }
}
