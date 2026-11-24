<?php

namespace App\Support\Concerns;

use App\Support\MediaLibrary;

/**
 * Shared media helpers for every public model (page, state, city, service).
 *
 * The database column is always preferred. Admins can upload a file (stored on
 * the public disk) or paste a full CDN URL - both are normalised here. The
 * MediaLibrary only supplies a category-based CDN fallback so a record can
 * never show a broken image.
 */
trait HasMedia
{
    /**
     * Category used to pick fallback CDN images. Models override this.
     */
    public function mediaCategory(): string
    {
        return 'city';
    }

    /**
     * Normalise any stored image value (upload path or CDN URL) for the web.
     */
    public function mediaUrl(?string $value): ?string
    {
        return MediaLibrary::url($value);
    }

    /**
     * Main / hero image for the entity.
     */
    public function imageUrl(): string
    {
        return MediaLibrary::url($this->image ?? null)
            ?? MediaLibrary::image($this->mediaCategory(), 0);
    }

    /**
     * Social share image (falls back to the main image).
     */
    public function ogImageUrl(): string
    {
        return MediaLibrary::url($this->og_image ?? null) ?? $this->imageUrl();
    }

    /**
     * Gallery image URLs (strings only).
     *
     * @return list<string>
     */
    public function galleryImages(int $limit = 12): array
    {
        $stored = collect($this->gallery ?? [])
            ->filter(fn (mixed $item): bool => ! is_array($item) || ($item['is_visible'] ?? true))
            ->map(fn (mixed $item): ?string => is_array($item)
                ? ($item['image'] ?? null)
                : (is_string($item) ? $item : null))
            ->map(fn (?string $value): ?string => MediaLibrary::url($value))
            ->filter()
            ->values()
            ->all();

        return MediaLibrary::gallery($this->mediaCategory(), $stored, $limit);
    }

    /**
     * Gallery as full items (image + optional title/category) for rendering.
     *
     * @return list<array{image: string, title: string, category: string}>
     */
    public function galleryItems(int $limit = 12): array
    {
        $stored = collect($this->gallery ?? [])
            ->filter(fn (mixed $item): bool => ! is_array($item) || ($item['is_visible'] ?? true))
            ->filter()
            ->values();

        if ($stored->isEmpty()) {
            $images = $this->galleryImages($limit);

            return array_map(
                fn (string $image): array => [
                    'image' => $image,
                    'title' => $this->name ?? 'Decoration',
                    'category' => $this->name ?? 'Decoration',
                ],
                $images,
            );
        }

        return $stored
            ->take($limit)
            ->map(function (mixed $item, int $index): array {
                if (is_string($item)) {
                    return [
                        'image' => MediaLibrary::url($item) ?? MediaLibrary::image($this->mediaCategory(), $index),
                        'title' => $this->name ?? 'Decoration',
                        'category' => $this->name ?? 'Decoration',
                    ];
                }

                return [
                    'image' => MediaLibrary::url($item['image'] ?? null)
                        ?? MediaLibrary::image($this->mediaCategory(), $index),
                    'title' => (string) ($item['title'] ?? $this->name ?? 'Decoration'),
                    'category' => (string) ($item['category'] ?? $this->name ?? 'Decoration'),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Nth gallery image, with a safe wrap-around.
     */
    public function galleryImageAt(int $index): string
    {
        $images = $this->galleryImages();

        if ($images === []) {
            return MediaLibrary::default();
        }

        return $images[$index % count($images)];
    }

    /**
     * Read a value from the JSON "content" column using dot notation.
     */
    public function contentValue(string $key, mixed $default = null): mixed
    {
        return data_get($this->content ?? [], $key, $default);
    }
}
