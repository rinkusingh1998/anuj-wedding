<?php

namespace App\Support\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared SEO + publication behaviour for every public content model.
 *
 * The same column set exists on pages, states, cities, localities, services,
 * packages and blog posts, so this trait keeps the rules in one place:
 *
 *  - a record is only indexable when it is visible, published and not deleted
 *  - robots, canonical, Open Graph and Twitter values fall back to the record
 *    content and then to the global website settings
 *  - nothing is invented: an empty description stays empty
 */
trait HasSeo
{
    /**
     * Absolute canonical URL for this record. Models override this.
     */
    abstract public function url(): string;

    public function seoCanonical(): string
    {
        return filled($this->canonical_url)
            ? (string) $this->canonical_url
            : $this->url();
    }

    public function isPublished(): bool
    {
        return $this->is_visible
            && ($this->published_at === null || $this->published_at->isPast());
    }

    public function isIndexable(): bool
    {
        return $this->isPublished()
            && (bool) ($this->robots_index ?? true);
    }

    public function seoRobots(): string
    {
        $index = $this->isIndexable() ? 'index' : 'noindex';
        $follow = (bool) ($this->robots_follow ?? true) ? 'follow' : 'nofollow';

        return $index.', '.$follow;
    }

    public function seoImageAlt(): string
    {
        return (string) ($this->image_alt ?: $this->seoTitle());
    }

    public function seoOgType(): string
    {
        return (string) ($this->og_type ?: 'website');
    }

    public function seoTwitterCard(): string
    {
        return (string) ($this->twitter_card ?: 'summary_large_image');
    }

    /**
     * Only published + visible records.
     */
    protected function scopePublished(Builder $query): Builder
    {
        return $query
            ->where($this->qualifyColumn('is_visible'), true)
            ->where(function (Builder $inner): void {
                $inner->whereNull($this->qualifyColumn('published_at'))
                    ->orWhere($this->qualifyColumn('published_at'), '<=', now());
            });
    }

    /**
     * Records that should appear in the XML sitemap.
     */
    protected function scopeIndexable(Builder $query): Builder
    {
        return $this->scopePublished($query)
            ->where($this->qualifyColumn('robots_index'), true);
    }
}
