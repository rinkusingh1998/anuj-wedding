<?php

namespace App\Support;

use App\Models\City;
use App\Models\Locality;
use App\Models\Package;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Redirect;
use App\Models\Service;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;

/**
 * Creates a 301 redirect when an admin changes a public slug.
 *
 * The old URL is never silently broken: the next request to it is sent to the
 * new URL. Existing redirects for the same "from" path are updated instead of
 * duplicated.
 */
final class SlugRedirector
{
    public static function handle(Model $model): void
    {
        $oldSlug = (string) $model->getOriginal('slug');
        $newSlug = (string) $model->getAttribute('slug');

        if ($oldSlug === '' || $newSlug === '' || $oldSlug === $newSlug) {
            return;
        }

        $paths = self::paths($model, $oldSlug, $newSlug);

        if ($paths === null) {
            return;
        }

        [$from, $to] = $paths;

        if ($from === $to || $from === '/') {
            return;
        }

        Redirect::query()->updateOrCreate(
            ['from_path' => Redirect::normalise($from)],
            [
                'to_path' => Redirect::normalise($to),
                'status_code' => 301,
                'is_active' => true,
                'note' => 'Automatically created after a slug change.',
            ],
        );
    }

    /**
     * @return array{0: string, 1: string}|null
     */
    private static function paths(Model $model, string $oldSlug, string $newSlug): ?array
    {
        return match (true) {
            $model instanceof Page => ['/'.$oldSlug, '/'.$newSlug],
            $model instanceof State => ['/'.$oldSlug, '/'.$newSlug],
            $model instanceof City => self::cityPaths($model, $oldSlug, $newSlug),
            $model instanceof Locality => self::localityPaths($model, $oldSlug, $newSlug),
            $model instanceof Service => ['/services/'.$oldSlug, '/services/'.$newSlug],
            $model instanceof Package => ['/packages/'.$oldSlug, '/packages/'.$newSlug],
            $model instanceof Post => ['/blogs/'.$oldSlug, '/blogs/'.$newSlug],
            $model instanceof PostCategory => ['/blogs/category/'.$oldSlug, '/blogs/category/'.$newSlug],
            default => null,
        };
    }

    /**
     * @return array{0: string, 1: string}|null
     */
    private static function cityPaths(City $city, string $oldSlug, string $newSlug): ?array
    {
        $stateSlug = $city->state?->slug;

        if (blank($stateSlug)) {
            return null;
        }

        return ['/'.$stateSlug.'/'.$oldSlug, '/'.$stateSlug.'/'.$newSlug];
    }

    /**
     * @return array{0: string, 1: string}|null
     */
    private static function localityPaths(Locality $locality, string $oldSlug, string $newSlug): ?array
    {
        $city = $locality->city;
        $stateSlug = $city?->state?->slug ?? $locality->state?->slug;

        if (blank($stateSlug) || blank($city?->slug)) {
            return null;
        }

        return [
            '/'.$stateSlug.'/'.$city->slug.'/area/'.$oldSlug,
            '/'.$stateSlug.'/'.$city->slug.'/area/'.$newSlug,
        ];
    }
}
