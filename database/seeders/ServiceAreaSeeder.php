<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Marks the eight primary active service regions and hides locations that are
 * outside the current service area from the public service-area listings.
 *
 * Nothing is deleted: every existing city stays in the database and can be
 * managed from the admin panel. Only the service-area flag, visibility and
 * sitemap inclusion change.
 *
 * Primary service areas:
 *   Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar, Ghaziabad, Hapur, Meerut,
 *   Mathura.
 */
class ServiceAreaSeeder extends Seeder
{
    /**
     * @var list<string>
     */
    private const PRIMARY_CITIES = [
        'Agra',
        'Aligarh',
        'Bulandshahr',
        'Gautam Buddh Nagar',
        'Ghaziabad',
        'Hapur',
        'Meerut',
        'Mathura',
    ];

    public function run(): void
    {
        $state = State::query()->where('slug', 'uttar-pradesh')->first();

        // "Gautam Buddh Nagar" is the district itself. Create its own page when
        // the imported city list only contains the towns inside it (Noida,
        // Greater Noida, Dadri, Jewar ...).
        if ($state) {
            City::query()->firstOrCreate(
                [
                    'state_id' => $state->id,
                    'slug' => Str::slug('Gautam Buddh Nagar'),
                ],
                [
                    'name' => 'Gautam Buddh Nagar',
                    'type' => 'city',
                    'district' => 'Gautam Buddh Nagar',
                    'is_visible' => true,
                    'is_featured' => true,
                    'is_service_area' => true,
                    'priority' => 95,
                    'sort_order' => 0,
                ],
            );
        }

        // Every location outside the eight primary regions stays in the
        // database, but is no longer listed as a service area. Its page remains
        // reachable (if it is visible), it is simply excluded from service-area
        // listings, navigation and the sitemap.
        City::query()->update(['is_service_area' => false]);

        City::query()
            ->whereIn('name', self::PRIMARY_CITIES)
            ->update([
                'is_service_area' => true,
                'is_visible' => true,
                'is_featured' => true,
            ]);

        // Delhi has no city records and is outside the current service area.
        // It is hidden, not deleted, so it can be re-enabled from the admin.
        State::query()->where('slug', 'delhi')->update(['is_visible' => false]);
    }
}
