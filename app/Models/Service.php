<?php

namespace App\Models;

use App\Observers\ServiceObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Database\Factories\ServiceFactory;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * A decoration service.
 *
 * Public URL: /services/{slug} plus one "{service} in {city}" page per city.
 */
#[ObservedBy(ServiceObserver::class)]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    use HasMedia;
    use HasSeo;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'short_name',
        'icon',
        'tagline',
        'description',
        'long_description',
        'image',
        'og_image',
        'gallery',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_visible',
        'priority',
        'sort_order',
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
            'priority' => 'integer',
            'sort_order' => 'integer',
            'gallery' => 'array',
            'content' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function mediaCategory(): string
    {
        return $this->slug ?: 'wedding-decoration';
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

    public function resolveRouteBindingQuery($query, $value, $field = null): BuilderContract
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('is_visible'), true);
    }

    public function path(): string
    {
        return 'services/'.$this->slug;
    }

    /**
     * Short description for service cards.
     *
     * The word limit is a single setting in config/website.php
     * ("service_card_words") so it can be changed without touching Blade.
     */
    public function shortDescription(?int $words = null): string
    {
        $words ??= (int) config('website.service_card_words', 26);

        $text = trim(strip_tags((string) ($this->description ?: $this->tagline)));

        return $text === '' ? '' : Str::words($text, max(6, $words), '…');
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
        return (string) ($this->meta_description ?: $this->tagline ?: $this->description ?: '');
    }
}
