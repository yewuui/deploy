<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use Concerns\ResolvesPublicFileUrl;
    use HasFactory;

    protected $fillable = [
        'program_id',
        'name',
        'subject',
        'role_title',
        'description',
        'expertise',
        'education',
        'campus',
        'photo',
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

    /**
     * Apakah guru memiliki informasi profil tambahan yang layak ditampilkan
     * pada panel detail di halaman publik.
     */
    public function hasProfileDetails(): bool
    {
        return filled($this->role_title)
            || filled($this->description)
            || filled($this->expertise)
            || filled($this->education)
            || filled($this->campus);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePublicFileUrl($this->photo);
    }
}
