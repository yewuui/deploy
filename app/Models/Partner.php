<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    use Concerns\ResolvesPublicFileUrl;
    use HasFactory;

    /** Warna latar bawaan kotak logo: putih. */
    public const DEFAULT_LOGO_BG_COLOR = '#FFFFFF';

    protected $fillable = [
        'program_id',
        'name',
        'website_url',
        'logo',
        'logo_bg_color',
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

    public function getLogoUrlAttribute(): ?string
    {
        return self::resolvePublicFileUrl($this->logo);
    }

    /**
     * Warna latar kotak logo yang dipakai halaman publik. Selalu hex 6 digit
     * agar bisa langsung dipasang sebagai inline style, dan selalu punya nilai
     * (putih bila admin belum memilih).
     */
    public function getLogoBgColorEffectiveAttribute(): string
    {
        return self::normalizeHexColor($this->logo_bg_color) ?? self::DEFAULT_LOGO_BG_COLOR;
    }

    /**
     * Warna teks yang kontras di atas warna latar kotak logo. Dipakai untuk
     * nama mitra ketika logo tidak tersedia.
     */
    public function getLogoBgTextColorAttribute(): string
    {
        return self::isDarkColor($this->logo_bg_color_effective) ? '#FFFFFF' : '#111827';
    }

    /**
     * Normalisasi warna dari form admin. Menerima "#RRGGBB", "RRGGBB",
     * "#RGB", atau "rgb(r, g, b)". Mengembalikan null bila tidak dikenali.
     */
    public static function normalizeHexColor(?string $color): ?string
    {
        if ($color === null) {
            return null;
        }

        $color = trim($color);

        if ($color === '') {
            return null;
        }

        if (preg_match('/^rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*[\d.]+\s*)?\)$/i', $color, $matches)) {
            $channels = [(int) $matches[1], (int) $matches[2], (int) $matches[3]];

            foreach ($channels as $channel) {
                if ($channel > 255) {
                    return null;
                }
            }

            return sprintf('#%02X%02X%02X', $channels[0], $channels[1], $channels[2]);
        }

        $hex = ltrim($color, '#');

        if (preg_match('/^[0-9A-Fa-f]{3}$/', $hex)) {
            return '#'.strtoupper($hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2]);
        }

        if (preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            return '#'.strtoupper($hex);
        }

        return null;
    }

    /**
     * Apakah warna cukup gelap sehingga teks di atasnya harus putih.
     */
    public static function isDarkColor(string $hex): bool
    {
        $hex = ltrim($hex, '#');

        $red = (int) hexdec(substr($hex, 0, 2));
        $green = (int) hexdec(substr($hex, 2, 2));
        $blue = (int) hexdec(substr($hex, 4, 2));

        $luminance = 0.2126 * $red + 0.7152 * $green + 0.0722 * $blue;

        return $luminance < 140;
    }
}
