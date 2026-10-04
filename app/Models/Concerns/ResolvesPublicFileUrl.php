<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesPublicFileUrl
{
    public static function resolvePublicFileUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);

        if (is_string($scheme) && in_array(strtolower($scheme), ['http', 'https'], true)) {
            return $value;
        }

        if (str_starts_with($value, 'images/')) {
            return asset($value);
        }

        return Storage::url($value);
    }
}
