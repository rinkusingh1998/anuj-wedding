<?php

namespace App\Observers;

use App\Models\Media;
use App\Support\MediaUsage;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Keeps the media catalogue in sync with the uploaded file.
 *
 *  - fills filename, mime type, size, dimensions and public URL automatically
 *  - refuses to permanently delete a file that is still referenced
 *  - removes the physical file only after the database row is force-deleted
 */
class MediaObserver
{
    public function saving(Media $media): void
    {
        if (auth()->check() && blank($media->uploaded_by)) {
            $media->uploaded_by = auth()->id();
        }

        $path = trim((string) $media->path);

        if ($media->isDirty('path')) {
            $media->filename = null;
            $media->mime_type = null;
            $media->size = null;
            $media->width = null;
            $media->height = null;
            $media->url = null;
        }

        if ($path === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $disk = $media->disk ?: 'public';

        try {
            $storage = Storage::disk($disk);
        } catch (\Throwable) {
            return;
        }

        if (! $storage->exists($path)) {
            return;
        }

        $media->filename = $media->filename ?: basename($path);
        $media->mime_type = $media->mime_type ?: $storage->mimeType($path);
        $media->size = $media->size ?: $storage->size($path);
        $media->url = $media->url ?: $storage->url($path);

        if (blank($media->title)) {
            $media->title = pathinfo($path, PATHINFO_FILENAME);
        }

        if (($media->width === null || $media->height === null) && $storage->exists($path)) {
            try {
                $dimensions = @getimagesize($storage->path($path));

                if (is_array($dimensions)) {
                    $media->width = $dimensions[0] ?? null;
                    $media->height = $dimensions[1] ?? null;
                }
            } catch (\Throwable) {
                // Non-image files simply keep null dimensions.
            }
        }
    }

    public function forceDeleting(Media $media): void
    {
        if (app(MediaUsage::class)->isInUse($media)) {
            throw new RuntimeException(
                'This file is still used by website content. Remove those references before permanently deleting it.',
            );
        }
    }

    public function forceDeleted(Media $media): void
    {
        $path = trim((string) $media->path);

        if ($path === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $disk = $media->disk ?: 'public';

        try {
            Storage::disk($disk)->delete($path);
        } catch (\Throwable) {
            // The database row is already gone; a missing file is harmless.
        }
    }
}
