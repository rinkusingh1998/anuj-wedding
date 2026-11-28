<?php

namespace App\Models;

use App\Observers\CityObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Database\Factories\CityFactory;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * City / town / tehsil / village landing page.
 *
 * Public URL: /{state}/{city} and /{state}/{city}/{service}.
 *
 * "is_service_area" marks the primary active service regions. Only those
 * cities are listed in the default service-area navigation and drop-downs;
 * every other record stays in the database (nothing is deleted) and remains
 * manageable from the admin panel.
 */
#[ObservedBy(CityObserver::class)]
class City extends Model
{
    /** @use HasFactory<CityFactory> */
    use HasFactory;

    use HasMedia;
    use HasSeo;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    public const TYPES = [
        'city' => 'City',
        'town' => 'Town',
        'tehsil' => 'Tehsil',
        'village' => 'Village',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'state_id',
        'name',
        'slug',
        'type',
        'district',
        'pincode',
        'latitude',
        'longitude',
        'is_visible',
        'is_featured',
        'is_service_area',
        'priority',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
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
            'gallery' => 'array',
            'content' => 'array',
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

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }

    #[Scope]
    protected function visible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    /**
     * The eight primary active service regions.
     */
    #[Scope]
    protected function serviceArea(Builder $query): Builder
    {
        return $query->where('is_service_area', true);
    }

    #[Scope]
    protected function featured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
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

    /**
     * A hidden city (or a city inside a hidden state) is not resolvable.
     */
    public function resolveRouteBindingQuery($query, $value, $field = null): BuilderContract
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('is_visible'), true);
    }

    /**
     * URL part, e.g. "uttar-pradesh/agra".
     */
    public function path(): string
    {
        return trim(($this->state?->slug ?? '').'/'.$this->slug, '/');
    }

    public function url(): string
    {
        return url($this->path());
    }

    /**
     * "{state}/{city}/{service}".
     */
    public function serviceUrl(Service $service): string
    {
        return url($this->path().'/'.$service->slug);
    }

    public function seoTitle(): string
    {
        return (string) ($this->meta_title ?: $this->name.' | '.SeoSetting::value('site_title', config('app.name')));
    }

    public function seoDescription(): string
    {
        $description = (string) ($this->meta_description ?: $this->sub_heading ?: '');

        // The imported meta text can repeat the city name ("Agra, Agra").
        if ($description !== '' && filled($this->name)) {
            $name = preg_quote((string) $this->name, '/');
            $description = (string) preg_replace(
                '/\b'.$name.',\s*'.$name.'\b/iu',
                (string) $this->name,
                $description,
            );
        }

        return $description;
    }

    /**
     * Only the eight primary service-area cities are indexable. Every other
     * city page stays reachable but is marked noindex and kept out of the
     * sitemap, exactly as the admin configured it.
     */
    public function isIndexable(): bool
    {
        return (bool) $this->is_service_area
            && $this->isPublished()
            && (bool) ($this->robots_index ?? true);
    }
}
