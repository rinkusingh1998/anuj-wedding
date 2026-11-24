<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

/**
 * Admin managed navigation menu item (header, footer or mobile menu).
 */
class NavigationItem extends Model
{
    /**
     * @var array<string, string>
     */
    public const MENUS = [
        'header' => 'Header menu',
        'footer' => 'Footer menu',
        'mobile' => 'Mobile menu',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'menu',
        'label',
        'description',
        'url',
        'route',
        'icon',
        'badge',
        'target',
        'group',
        'meta',
        'is_visible',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
            'meta' => 'array',
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Children, their children and so on, ordered for menu rendering.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Optional icon shown next to a mega menu group heading.
     */
    public function groupIcon(): ?string
    {
        return $this->meta['group_icon'] ?? $this->icon;
    }

    /**
     * Whether this item opens the full width mega menu instead of a plain
     * dropdown. Only meaningful for root header items with children.
     */
    public function isMega(): bool
    {
        return (bool) ($this->meta['is_mega'] ?? false)
            || $this->children->contains(fn (self $child): bool => $child->children->isNotEmpty());
    }

    /**
     * Whether the item should be rendered as the header call to action button.
     */
    public function isCta(): bool
    {
        return (bool) ($this->meta['is_cta'] ?? false);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function scopeMenu(Builder $query, string $menu): Builder
    {
        return $query->where('menu', $menu);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function resolvedUrl(): string
    {
        if (filled($this->route) && Route::has($this->route)) {
            return route($this->route);
        }

        $url = (string) $this->url;

        if ($url === '') {
            return url('/');
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '#')) {
            return $url;
        }

        return url($url);
    }
}
