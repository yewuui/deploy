<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Concerns\ResolvesPublicFileUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Penghapusan berkas media yang diunggah lewat form admin.
 *
 * Kolom media bisa berisi tiga jenis nilai, sehingga tidak semuanya boleh
 * dihapus dari disk:
 *
 * - `achievements/abc.jpg` -> berkas milik storage, aman dihapus.
 * - `images/logo-x.png`   -> aset repo yang dipakai bersama, JANGAN dihapus.
 * - `https://cdn.../a.svg` -> URL eksternal, JANGAN dihapus.
 *
 * Guard-nya sengaja dibuat sama dengan {@see ResolvesPublicFileUrl}
 * supaya pembacaan dan penghapusan memakai satu aturan yang sama.
 */
trait DeletesStoredMedia
{
    protected function updateWithStoredMedia(Model $model, array $attributes, string $mediaAttribute, ?UploadedFile $file, string $directory): void
    {
        if ($file === null) {
            $model->update($attributes);

            return;
        }

        $oldPath = $model->getAttribute($mediaAttribute);
        $oldPath = is_string($oldPath) ? $oldPath : null;
        $newPath = $file->store($directory, 'public');

        if (! is_string($newPath) || $newPath === '') {
            throw ValidationException::withMessages([
                $mediaAttribute => 'Berkas gagal disimpan. Silakan coba lagi.',
            ]);
        }

        $attributes[$mediaAttribute] = $newPath;

        try {
            $updated = $model->update($attributes);
        } catch (Throwable $exception) {
            $this->deleteStoredMedia($newPath);

            throw $exception;
        }

        if (! $updated) {
            $this->deleteStoredMedia($newPath);

            throw ValidationException::withMessages([
                $mediaAttribute => 'Perubahan data gagal disimpan. Silakan coba lagi.',
            ]);
        }

        $this->deleteStoredMedia($oldPath);
    }

    /**
     * Hapus berkas dari disk public bila nilai kolomnya benar-benar path
     * milik storage. Nilai null, URL, dan aset repo diabaikan diam-diam.
     */
    protected function deleteStoredMedia(?string $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }

        $scheme = parse_url($path, PHP_URL_SCHEME);

        if (is_string($scheme) && in_array(strtolower($scheme), ['http', 'https'], true)) {
            return;
        }

        if (str_starts_with($path, 'images/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
