<?php

namespace App\Support;

use App\Models\BusinessProfile;
use App\Models\City;
use App\Models\Locality;
use App\Models\Media;
use App\Models\Package;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SeoSetting;
use App\Models\Service;
use App\Models\State;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

/**
 * Finds every place a media file is referenced.
 *
 * Media values are stored as plain strings (a public-disk path or an absolute
 * CDN URL) inside normal columns and JSON columns. This service checks both so
 * a file that is still used by a page, city, service, blog post or global
 * setting can never be force-deleted by accident.
 */
final class MediaUsage
{
    /**
     * Model => columns that can hold a media reference.
     *
     * JSON columns are searched as text, which is safe because the stored value
     * is a normal string path/URL.
     *
     * @var array<class-string<Model>, list<string>>
     */
    private const TARGETS = [
        Page::class => ['image', 'og_image', 'twitter_image', 'gallery', 'content', 'faqs'],
        State::class => ['image', 'og_image', 'twitter_image', 'gallery', 'content', 'faqs'],
        City::class => ['image', 'og_image', 'twitter_image', 'gallery', 'content', 'faqs'],
        Locality::class => ['image', 'og_image', 'twitter_image', 'gallery', 'content', 'faqs'],
        Service::class => ['image', 'og_image', 'twitter_image', 'gallery', 'content'],
        Package::class => ['image', 'og_image', 'twitter_image', 'gallery', 'content'],
        Post::class => ['image', 'og_image', 'twitter_image', 'gallery', 'body'],
        PostCategory::class => ['twitter_image'],
        PageBlock::class => ['fields'],
        Testimonial::class => ['image'],
        BusinessProfile::class => ['logo', 'mobile_logo', 'favicon', 'og_image'],
        SeoSetting::class => ['value'],
    ];

    /**
     * Human readable list of records that use this media file.
     *
     * @return list<string>
     */
    public function usages(Media $media): array
    {
        $needles = $this->needles($media);

        if ($needles === []) {
            return [];
        }

        $usages = [];

        foreach (self::TARGETS as $model => $columns) {
            $query = $model::query();

            $query->where(function ($query) use ($columns, $needles): void {
                foreach ($columns as $column) {
                    foreach ($needles as $needle) {
                        $query->orWhere($column, 'like', '%'.$needle.'%');
                    }
                }
            });

            foreach ($query->limit(20)->get() as $record) {
                $label = $this->label($record);

                if ($label !== null) {
                    $usages[] = $label;
                }
            }
        }

        return array_values(array_unique($usages));
    }

    public function isInUse(Media $media): bool
    {
        return $this->usages($media) !== [];
    }

    /**
     * Values that can appear in content: the stored path, the generated URL,
     * the bare filename and the storage-prefixed path.
     *
     * @return list<string>
     */
    private function needles(Media $media): array
    {
        $values = array_filter([
            $media->path,
            $media->url,
            $media->filename,
        ]);

        $needles = [];

        foreach ($values as $value) {
            $value = trim((string) $value);

            if ($value === '') {
                continue;
            }

            $needles[] = $value;
            $needles[] = ltrim($value, '/');

            if (str_starts_with($value, 'storage/')) {
                $needles[] = substr($value, strlen('storage/'));
            }
        }

        return array_values(array_unique($needles));
    }

    private function label(Model $record): ?string
    {
        $name = $record->getAttribute('name')
            ?? $record->getAttribute('title')
            ?? $record->getAttribute('business_name')
            ?? $record->getAttribute('question')
            ?? $record->getAttribute('key')
            ?? null;

        if (! filled($name)) {
            return null;
        }

        $model = class_basename($record);

        return $model.': '.$name;
    }
}
