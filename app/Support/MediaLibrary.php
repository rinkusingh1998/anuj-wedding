<?php

namespace App\Support;

use App\Models\Media;
use App\Models\SeoSetting;

/**
 * Central image library.
 *
 * Every public model stores its own image path in the database. This class only
 * provides the fallback values, grouped by category (page slug, service slug or
 * content type). That means the admin panel is the source of truth at runtime,
 * while a fresh install still looks complete using the project image folders.
 */
final class MediaLibrary
{
    public const DEFAULT_IMAGE = 'assets/banner/wedding-decoration.png';

    /**
     * Local image paths grouped by category.
     *
     * @var array<string, list<string>>
     */
    private const CATEGORY_IMAGES = [
        // Static pages
        'home' => [
            'assets/banner/wedding-decoration.png',
            'assets/banner/engagement-decoration.png',
            'assets/banner/haldi-decoration.png',
            'assets/banner/mehendi-decoration.png',
            'assets/banner/balloon-decoration.png',
            'assets/banner/fogg-matka-entry.png',
            'assets/banner/birthday-decoration.png',
        ],
        'about-us' => [
            'assets/images/about-us-main.png',
            'assets/images/about-us-accent.png',
            'assets/images/about-us-detail.png',
            'assets/images/breadcrumb-background.png',
        ],
        'services' => [
            'assets/services/wedding-decoration.png',
            'assets/services/engagement-decoration.png',
            'assets/services/haldi-decoration.png',
            'assets/services/mehendi-decoration.png',
            'assets/services/chunni-deco.png',
            'assets/services/family-function-decoration.png',
            'assets/services/honeymoon-decoration.png',
            'assets/services/ballon-deco.png',
            'assets/services/mattka-deco.png',
            'assets/services/birthday-deco.png',
            'assets/services/festival-decoration.png',
            'assets/services/comp-event.png',
        ],
        'gallery' => [
            'assets/services/wedding-decoration.png',
            'assets/services/engagement-decoration.png',
            'assets/services/haldi-decoration.png',
            'assets/services/mehendi-decoration.png',
            'assets/services/chunni-deco.png',
            'assets/services/family-function-decoration.png',
            'assets/services/honeymoon-decoration.png',
            'assets/services/ballon-deco.png',
            'assets/services/mattka-deco.png',
            'assets/services/birthday-deco.png',
            'assets/services/festival-decoration.png',
            'assets/services/comp-event.png',
        ],
        'packages' => [
            'assets/packages/basic.png',
            'assets/packages/premium.png',
            'assets/packages/royal.png',
            'assets/packages/honeymoon-decoration.png',
        ],
        'blogs' => [
            'assets/blogs/01.png',
            'assets/blogs/02.png',
            'assets/blogs/03.png',
            'assets/blogs/04.png',
            'assets/blogs/033.png',
            'assets/blogs/05.png',
            'assets/blogs/06.png',
            'assets/blogs/07.png',
        ],
        'faq' => [
            'assets/services/wedding-decoration.png',
            'assets/services/engagement-decoration.png',
            'assets/services/haldi-decoration.png',
            'assets/services/mehendi-decoration.png',
            'assets/services/chunni-deco.png',
            'assets/services/family-function-decoration.png',
            'assets/services/honeymoon-decoration.png',
            'assets/services/ballon-deco.png',
            'assets/services/mattka-deco.png',
            'assets/services/birthday-deco.png',
            'assets/services/festival-decoration.png',
        ],
        'contact-us' => [
            'assets/images/about-us-main.png',
            'assets/images/about-us-detail.png',
        ],
        'locations' => [
            'assets/banner/wedding-decoration.png',
            'assets/banner/haldi-decoration.png',
        ],
        'wedding-event-decoration-guide' => [
            'assets/services/wedding-decoration.png',
            'assets/services/engagement-decoration.png',
            'assets/services/haldi-decoration.png',
        ],
        'blog-detail' => [
            'assets/blogs/01.png',
            'assets/blogs/02.png',
            'assets/blogs/03.png',
        ],
        'service-detail' => [
            'assets/services/wedding-decoration.png',
            'assets/services/engagement-decoration.png',
            'assets/services/haldi-decoration.png',
        ],

        // Services (one category per service slug)
        'wedding-decoration' => [
            'assets/services/wedding-decoration.png',
            'assets/services/comp-event.png',
            'assets/services/mehendi-decoration.png',
            'assets/services/chunni-deco.png',
        ],
        'flower-decoration' => [
            'assets/services/wedding-decoration.png',
            'assets/services/mehendi-decoration.png',
        ],
        'stage-backdrop-decoration' => [
            'assets/services/comp-event.png',
            'assets/services/wedding-decoration.png',
        ],
        'entry-decoration' => [
            'assets/services/entry-gate-decoration.png',
            'assets/services/entry-gate-decoration2.png',
        ],
        'haldi-decoration' => [
            'assets/services/haldi-decoration.png',
            'assets/services/mehendi-decoration.png',
            'assets/services/wedding-decoration.png',
        ],
        'mehendi-decoration' => [
            'assets/services/mehendi-decoration.png',
            'assets/services/haldi-decoration.png',
            'assets/services/wedding-decoration.png',
        ],
        'chunni-ceremony' => [
            'assets/services/chunni-deco.png',
            'assets/services/mehendi-decoration.png',
        ],
        'fogg-matka' => [
            'assets/services/mattka-deco.png',
            'assets/services/comp-event.png',
        ],
        'balloon-decoration' => [
            'assets/services/ballon-deco.png',
            'assets/services/birthday-deco.png',
        ],
        'birthday-decoration' => [
            'assets/services/birthday-deco.png',
            'assets/services/ballon-deco.png',
        ],
        'wedding-counters' => [
            'assets/services/entry-gate-decoration2.png',
            'assets/services/comp-event.png',
        ],
        'complete-event-decor' => [
            'assets/services/comp-event.png',
            'assets/services/wedding-decoration.png',
        ],
        'engagement-decoration' => [
            'assets/services/engagement-decoration.png',
            'assets/services/wedding-decoration.png',
        ],
        'family-function-decoration' => [
            'assets/services/family-function-decoration.png',
            'assets/services/haldi-decoration.png',
        ],
        'honeymoon-decoration' => [
            'assets/services/honeymoon-decoration.png',
            'assets/services/wedding-decoration.png',
        ],
        'festival-decoration' => [
            'assets/services/festival-decoration.png',
            'assets/services/haldi-decoration.png',
        ],

        // States / cities
        'uttar-pradesh' => [
            'assets/banner/wedding-decoration.png',
            'assets/banner/haldi-decoration.png',
            'assets/banner/engagement-decoration.png',
        ],
        'delhi' => [
            'assets/banner/engagement-decoration.png',
            'assets/banner/birthday-decoration.png',
        ],
        'city' => [
            'assets/banner/wedding-decoration.png',
            'assets/banner/engagement-decoration.png',
            'assets/banner/haldi-decoration.png',
            'assets/banner/mehendi-decoration.png',
            'assets/banner/balloon-decoration.png',
            'assets/banner/fogg-matka-entry.png',
            'assets/banner/birthday-decoration.png',
        ],
    ];

    /**
     * All local images for a category.
     *
     * @return list<string>
     */
    public static function images(string $category): array
    {
        return self::CATEGORY_IMAGES[$category] ?? [self::DEFAULT_IMAGE];
    }

    /**
     * Turn a stored value into a browser-friendly URL.
     *
     * Admins can either upload a file (stored as "pages/abc.jpg" on the public
     * disk) or keep a full CDN URL. Both are supported everywhere.
     */
    public static function url(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $value = trim((string) $value);

        if (str_starts_with($value, '//')) {
            return 'https:'.$value;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, 'assets/') || str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/'.ltrim($value, '/'));
    }

    /**
     * Width and height of a library image, when they are known.
     *
     * Social networks need the real dimensions of the image that is actually
     * shared. They are read from the media catalogue, so no guessed 1200x630
     * values are emitted for images whose size is unknown.
     *
     * @return array{width: int, height: int}|null
     */
    public static function dimensions(?string $value): ?array
    {
        if (! filled($value)) {
            return null;
        }

        $value = trim((string) $value);
        $path = str_starts_with($value, 'http') || str_starts_with($value, '//')
            ? parse_url($value, PHP_URL_PATH)
            : $value;

        if (! filled($path)) {
            return null;
        }

        $path = ltrim((string) $path, '/');
        $storagePath = preg_replace('#^storage/#', '', $path);

        $media = Media::query()
            ->where(function ($query) use ($value, $path, $storagePath): void {
                $query->where('url', $value)
                    ->orWhere('path', $value)
                    ->orWhere('path', $path)
                    ->orWhere('path', $storagePath)
                    ->orWhere('path', 'storage/'.$storagePath);
            })
            ->first();

        if ($media === null || ! $media->width || ! $media->height) {
            return null;
        }

        return [
            'width' => (int) $media->width,
            'height' => (int) $media->height,
        ];
    }

    /**
     * Best image for a category.
     *
     * A value stored in the database always wins; the local library is only the
     * fallback for records that have not been given an image yet.
     */
    public static function image(string $category, int $index = 0, ?string $stored = null): string
    {
        if (filled($stored)) {
            return (string) $stored;
        }

        $images = self::images($category);

        return $images[$index % count($images)] ?? self::DEFAULT_IMAGE;
    }

    /**
     * A category gallery, optionally merged with stored values.
     *
     * @param  list<string>  $stored
     * @return list<string>
     */
    public static function gallery(string $category, array $stored = [], int $limit = 12): array
    {
        $images = array_values(array_filter($stored));

        if ($images === []) {
            $images = self::images($category);
        }

        return array_slice($images, 0, $limit);
    }

    /**
     * Website-wide default image (admin configurable through settings).
     */
    public static function default(): string
    {
        $configured = self::url(SeoSetting::value('og_image'));

        return $configured ?? self::DEFAULT_IMAGE;
    }
}
