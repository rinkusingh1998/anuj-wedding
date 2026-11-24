<?php

namespace App\Support;

use App\Models\SeoSetting;
use Illuminate\Database\Eloquent\Model;

/**
 * Admin managed section labels and headings.
 *
 * Websites repeat labels like "Our Services" or "Areas Near {city}" on many
 * templates. Instead of hard-coding them in Blade, they live in the global
 * settings and can be overridden per record through its `content.labels`
 * JSON (e.g. a city page can use a different heading than the rest).
 *
 * Placeholders such as {city}, {state} and {service} are replaced with real
 * record data, never with invented copy.
 */
final class Labels
{
    /**
     * @param  array<string, string>  $replace
     */
    public static function get(string $key, array $replace = [], ?Model $model = null): ?string
    {
        $override = $model ? data_get($model->content ?? [], 'labels.'.$key) : null;

        if (filled($override)) {
            $value = (string) $override;
        } else {
            $value = SeoSetting::value($key) ?? SeoSetting::value('label_'.$key);
        }

        if (! filled($value)) {
            return null;
        }

        foreach ($replace as $name => $replacement) {
            $value = str_replace('{'.$name.'}', (string) $replacement, $value);
        }

        return $value;
    }
}
