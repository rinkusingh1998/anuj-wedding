<?php

namespace App\Models;

use App\Observers\LocalityObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A locality / area / neighbourhood inside a city.
 *
 * Public URL: /{state}/{city}/{locality}
 */
#[ObservedBy(LocalityObserver::class)]
class Locality extends Model
{
    use HasMedia;
    use HasSeo;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    public const TYPES = [
        'locality' => 'Locality',
        'area' => 'Area',
        'neighbourhood' => 'Neighbourhood',
        'village' => 'Village',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'state_id',
        'city_id',
        'name',
        'slug',
        'type',
        'pincode',
        'latitude',
        'longitude',
        'is_visible',
        'is_featured',
        'is_service_area',
        'priority',
        'sort_order',
        'heading',
        'sub_heading',
        'intro_content',
        'about_content',
        'highlights',
        'faqs',
        'content',
        'image',
        'og_image',
        'gallery',
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
            'is_featured' => 'boolean',
            'is_service_area' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'priority' => 'integer',
            'sort_order' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'highlights' => 'array',
            'faqs' => 'array',
            'content' => 'array',
            'gallery' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function mediaCategory(): string
    {
        return 'city';
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_visible', true)
            ->where(fn (Builder $query): Builder => $query
                ->whereNull('published_at')
                ->orWhere('published_at', '<=', now()));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Only localities whose city and state are public too.
     */
    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query
            ->published()
            ->whereHas('city', fn (Builder $city): Builder => $city->where('is_visible', true))
            ->whereHas('state', fn (Builder $state): Builder => $state->where('is_visible', true));
    }

    public function path(): string
    {
        return trim(
            ($this->state?->slug ?? '').'/'.($this->city?->slug ?? '').'/area/'.$this->slug,
            '/'
        );
    }

    public function url(): string
    {
        return url($this->path());
    }

    public function seoTitle(): string
    {
        $custom = (string) $this->meta_title;

        if ($custom !== '') {
            return $custom;
        }

        // The same area name can exist in several cities ("Civil Lines"), so
        // the city is part of the fallback title to keep every page unique.
        $place = $this->name;

        if (filled($this->city?->name)) {
            $place .= ', '.$this->city->name;
        }

        return $place.' | Wedding & Event Decoration';
    }

    public function seoDescription(): string
    {
        return (string) ($this->meta_description ?: $this->sub_heading ?: '');
    }
}
