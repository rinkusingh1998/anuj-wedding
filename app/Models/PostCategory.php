<?php

namespace App\Models;

use App\Observers\PostCategoryObserver;
use App\Support\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog category. Public URL: /blog/category/{slug}
 */
#[ObservedBy(PostCategoryObserver::class)]
class PostCategory extends Model
{
    use HasSeo;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_visible',
        'sort_order',
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
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function path(): string
    {
        return 'blogs/category/'.$this->slug;
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
