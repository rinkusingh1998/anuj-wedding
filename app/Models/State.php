<?php

namespace App\Models;

use App\Observers\StateObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Database\Factories\StateFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * State landing page. Public URL: /{state}.
 *
 * Hiding a state hides every city, service-in-city and locality page under it.
 */
#[ObservedBy(StateObserver::class)]
class State extends Model
{
    /** @use HasFactory<StateFactory> */
    use HasFactory;

    use HasMedia;
    use HasSeo;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'region',
        'is_visible',
        'is_default',
        'priority',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'heading',
        'sub_heading',
        'intro_content',
        'about_content',
        'faqs',
        'content',
        'image',
        'og_image',
        'gallery',
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
            'is_default' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'priority' => 'integer',
            'sort_order' => 'integer',
            'faqs' => 'array',
            'gallery' => 'array',
            'content' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function mediaCategory(): string
    {
        return $this->slug ?: 'uttar-pradesh';
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }

    #[Scope]
    protected function visible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function path(): string
    {
        return (string) $this->slug;
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
        return (string) ($this->meta_description ?: $this->sub_heading ?: '');
    }
}
