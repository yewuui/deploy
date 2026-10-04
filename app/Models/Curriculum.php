<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curriculums';

    protected $fillable = [
        'program_id',
        'grade',
        'type',
        'semester',
        'title',
        'description',
        'topics',
        'image',
        'week_start',
        'week_end',
        'color_theme',
        'icon',
        'order_number',
    ];

    protected $casts = [
        'topics' => 'array',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://') || str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'pkl' => 'Praktik Kerja Lapangan (PKL)',
            'proyek' => 'Proyek / Capstone',
            'ukk' => 'Uji Kompetensi Keahlian (UKK)',
            'kesiapan_kerja' => 'Kesiapan Kerja',
            'dokumentasi' => 'Dokumentasi Kegiatan',
            default => (int) $this->grade === 12 ? 'Program / Kegiatan' : 'Materi Pembelajaran',
        };
    }
}
