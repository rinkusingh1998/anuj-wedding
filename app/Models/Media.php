<?php

namespace App\Models;

use App\Observers\MediaObserver;
use App\Support\MediaLibrary;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A file in the central media library.
 *
 * Models keep storing the path / URL string in their own column, so existing
 * content keeps working. This table adds the catalogue an admin needs:
 * search, alt text, captions, collections and safe deletion.
 */
#[ObservedBy(MediaObserver::class)]
class Media extends Model
{
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'disk',
        'path',
        'url',
        'filename',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'title',
        'caption',
        'collection',
        'folder',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    /**
     * Public URL of this file.
     */
    public function publicUrl(): ?string
    {
        return MediaLibrary::url($this->path ?: $this->url);
    }

    /**
     * Add an uploaded file to the catalogue (idempotent).
     *
     * Every image uploaded through the admin panel is registered here, so the
     * Media Library shows the same files that pages, cities, services and blog
     * posts use. The observer fills filename, mime type, size and dimensions.
     */
    public static function register(
        string $path,
        string $disk = 'public',
        string $collection = 'general',
        ?string $folder = null,
    ): ?self {
        $path = trim($path);

        if ($path === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        return self::query()->firstOrCreate(
            ['path' => $path],
            [
                'disk' => $disk,
                'collection' => $collection,
                'folder' => $folder ?: (dirname($path) !== '.' ? dirname($path) : null),
            ],
        );
    }

    public function scopeCollection(Builder $query, string $collection): Builder
    {
        return $query->where('collection', $collection);
    }

    /**
     * Value that should be stored on the owning record.
     */
    public function storedValue(): string
    {
        return (string) ($this->path ?: $this->url);
    }
}
