<?php

use App\Models\SeoSetting;
use App\Support\Labels;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| Small Blade helpers
|--------------------------------------------------------------------------
| These two functions exist only to keep templates short. Instead of
| writing the full class path 150 times, a view reads:
|
|   {{ site_label('home_hero_title') }}
|   {{ site_setting('service_areas_intro') }}
|
| Both return exactly what the classes return, so behaviour never changes.
*/

if (! function_exists('site_label')) {
    /**
     * An admin managed label, with optional {city}/{state}/{service} placeholders
     * and an optional record override.
     *
     * @param  array<string, string|int|float|null>  $replace
     */
    function site_label(string $key, array $replace = [], ?Model $model = null): ?string
    {
        return Labels::get($key, $replace, $model);
    }
}

if (! function_exists('site_setting')) {
    /**
     * A value from the global website settings.
     */
    function site_setting(string $key): ?string
    {
        return SeoSetting::value($key);
    }
}
