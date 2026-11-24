<?php

namespace App\Models;

use App\Observers\PostObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog post. Public URL: /blog/{slug}
 */
#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    use HasMedia;
    use HasSeo;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'post_category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'author',
        'reading_minutes',
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
            'reading_minutes' => 'integer',
            'gallery' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function mediaCategory(): string
    {
        return 'blogs';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }

    public function path(): string
    {
        return 'blogs/'.$this->slug;
    }

    public function url(): string
    {
        return url($this->path());
    }

    public function seoTitle(): string
    {
        return (string) ($this->meta_title ?: $this->title.' | '.SeoSetting::value('site_title', config('app.name')));
    }

    public function seoDescription(): string
    {
        return (string) ($this->meta_description ?: $this->excerpt ?: '');
    }

    public function seoOgType(): string
    {
        return (string) ($this->og_type ?: 'article');
    }
}
