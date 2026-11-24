<?php

namespace App\Models;

use App\Observers\PageObserver;
use App\Support\Concerns\HasMedia;
use App\Support\Concerns\HasSeo;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * A website page (Home, About Us, Contact Us, legal pages, ...).
 *
 * Everything a visitor reads on the page comes from this table (or from the
 * page_blocks / content sections attached to it). No marketing copy is
 * hard-coded in Blade.
 */
#[ObservedBy(PageObserver::class)]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
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
        'template',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'heading',
        'sub_heading',
        'intro_content',
        'image',
        'og_image',
        'gallery',
        'content',
        'faqs',
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
            'faqs' => 'array',
            'published_at' => 'datetime',
        ];
    }

    /**
     * CDN fallback category is the page slug (home, about-us, gallery ...).
     */
    public function mediaCategory(): string
    {
        return $this->slug ?: 'home';
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    /**
     * All visible blocks, keyed by their block key.
     *
     * @return Collection<string, PageBlock>
     */
    public function blockMap(): Collection
    {
        if (! $this->relationLoaded('blocks')) {
            $this->load(['blocks' => fn (HasMany $query) => $query->visible()]);
        }

        return $this->blocks->keyBy('key');
    }

    /**
     * A single block, or null when the admin has not configured it.
     */
    public function block(string $key): ?PageBlock
    {
        return $this->blockMap()->get($key);
    }

    public function hasBlock(string $key): bool
    {
        return $this->block($key) !== null;
    }

    /**
     * Field value of a block, e.g. $page->blockField('about.story', 'heading').
     */
    public function blockField(string $key, string $field, mixed $default = null): mixed
    {
        return $this->block($key)?->get($field, $default);
    }

    /**
     * Text value shortcut. Returns null (never a marketing fallback) so a Blade
     * template can simply skip empty elements.
     */
    public function text(string $key): ?string
    {
        $value = $this->blockField($key, 'value');

        return filled($value) ? (string) $value : null;
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

    /**
     * URL path, e.g. "about-us". The home page is "/".
     */
    public function path(): string
    {
        return $this->slug === 'home' ? '' : (string) $this->slug;
    }

    public function url(): string
    {
        return url($this->path() === '' ? '/' : $this->path());
    }

    public function seoTitle(): string
    {
        if (filled($this->meta_title)) {
            return (string) $this->meta_title;
        }

        if ($this->slug === 'home') {
            return (string) SeoSetting::value('home_meta_title', $this->name);
        }

        return $this->name.' | '.SeoSetting::value('site_title', config('app.name'));
    }

    public function seoDescription(): string
    {
        if (filled($this->meta_description)) {
            return (string) $this->meta_description;
        }

        if ($this->slug === 'home') {
            return (string) SeoSetting::value('home_meta_description', '');
        }

        return (string) ($this->sub_heading ?: '');
    }
}
