<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A single question/answer pair from the admin managed FAQ bank.
 */
class Faq extends Model
{
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'question',
        'answer',
        'category',
        'scope',
        'is_visible',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
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
     * Shape used by the FAQ schema / Blade components.
     *
     * @return array{question: string, answer: string, category: string}
     */
    public function toFaqArray(): array
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
            'category' => $this->category ?: 'general',
        ];
    }
}
