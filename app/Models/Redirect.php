<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Admin managed redirect. Old URLs keep working after a slug change.
 */
class Redirect extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'from_path',
        'to_path',
        'status_code',
        'is_active',
        'hits',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'is_active' => 'boolean',
            'hits' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Normalise a path for storage / lookup: always starts with "/" and has no
     * query string, so the same rule matches both admin input and requests.
     */
    public static function normalise(string $path): string
    {
        $path = trim(parse_url($path, PHP_URL_PATH) ?: $path);
        $path = '/'.ltrim($path, '/');
        $path = rtrim($path, '/');

        return $path === '' ? '/' : $path;
    }

    public function targetUrl(): string
    {
        $path = (string) $this->to_path;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url($path);
    }
}
