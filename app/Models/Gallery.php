<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'image',
        'website_url',
        'alt',
        'group',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image);
    }

    /**
     * Tautan keluar untuk logo bullion, misalnya organizer lomba. Hanya skema
     * http dan https yang diizinkan supaya nilai dari database tidak bisa
     * menjadi `javascript:` atau skema lain yang dieksekusi peramban.
     */
    public function getSafeWebsiteUrlAttribute(): ?string
    {
        $url = is_string($this->website_url) ? trim($this->website_url) : '';

        if ($url === '') {
            return null;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if (! is_string($scheme) || ! in_array(strtolower($scheme), ['http', 'https'], true)) {
            return null;
        }

        return $url;
    }
}
