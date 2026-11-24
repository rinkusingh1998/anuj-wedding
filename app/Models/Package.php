<?php

namespace App\Models;

use App\Observers\PackageObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Decoration package. Public URL: /packages/{slug}
 */
#[ObservedBy(PackageObserver::class)]
class Package extends Model
{
    use HasMedia;
    use HasSeo;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'label',
        'badge',
        'price_from',
        'price_note',
        'description',
        'features',
        'content',
        'image',
        'og_image',
        'gallery',
        'is_visible',
        'is_featured',
        'priority',
        'sort_order',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'og_title',
        'og_description',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'focus_keyword',
        'image_alt',
        'eyebrow',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'priority' => 'integer',
            'sort_order' => 'integer',
            'features' => 'array',
            'content' => 'array',
            'gallery' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function mediaCategory(): string
    {
        return 'packages';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function path(): string
    {
        return 'packages/'.$this->slug;
    }

    public function url(): string
    {
        return url($this->path());
    }

    public function seoTitle(): string
    {
        return (string) ($this->meta_title ?: $this->name.' | '.SeoSetting::value('site_title', config('app.name')));
    }

    public function seoDescription(): string
    {
        return (string) ($this->meta_description ?: $this->description ?: '');
    }
}
