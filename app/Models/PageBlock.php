<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One editable content block on a page.
 *
 * Blocks are the flexible section system: an admin can edit the fields of an
 * existing block, reorder it, hide it, or add a new block (rendered by the
 * generic <x-content-sections> component) without a code change.
 */
class PageBlock extends Model
{
    /**
     * @var array<string, string>
     */
    public const TYPES = [
        'text' => 'Text + image',
        'hero' => 'Hero',
        'cta' => 'Call to action',
        'gallery' => 'Gallery',
        'list' => 'List',
        'html' => 'Custom HTML',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'page_id',
        'key',
        'type',
        'label',
        'fields',
        'is_visible',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Field value with dot notation, e.g. $block->get('heading').
     */
    public function get(string $field, mixed $default = null): mixed
    {
        return data_get($this->fields ?? [], $field, $default);
    }
}
