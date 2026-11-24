<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A website enquiry submitted through any public form.
 */
class Enquiry extends Model
{
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'converted' => 'Converted',
        'closed' => 'Closed',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'service',
        'event_type',
        'event_date',
        'event_time',
        'city',
        'event_address',
        'budget',
        'message',
        'source_page',
        'source_url',
        'status',
        'ip_address',
        'user_agent',
    ];

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
