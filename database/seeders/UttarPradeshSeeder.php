<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Uttar Pradesh ke state + shehar + gaon import karta hai.
 *
 * Data `database/seeders/data/uttar-pradesh.php` se aata hai.
 *
 * Zaroori baat:
 *   - Jo city admin panel me pehle se hai, uske sirf naam/district/type update hote hain.
 *     Admin ka "Show/Hide" (is_visible) aur "Featured" decision kabhi overwrite nahi hota.
 *   - Naya shehar add karne ke baad uska page aur sitemap entry automatic ban jaati hai.
 */
class UttarPradeshSeeder extends Seeder
{
    /**
     * Is run me kaun-kaun se slug already use ho chuke hain (duplicate naam se bachne ke liye).
     *
     * @var array<string, string>
     */
    private array $usedSlugs = [];

    /**
     * Duplicate reh gaye naam (report ke liye).
     *
     * @var list<string>
     */
    private array $skipped = [];

    public function run(): void
    {
        $data = require database_path('seeders/data/uttar-pradesh.php');

        $state = $this->syncState($data['state']);

        foreach ($data['extra_states'] ?? [] as $extraState) {
            $this->syncState($extraState);
        }

        $created = 0;
        $updated = 0;

        foreach ($data['districts'] as $district => $places) {
            foreach ($places as $index => $name) {
                $isDistrictHeadquarters = $index === 0;

                $result = $this->syncCity(
                    state: $state,
                    name: $name,
                    district: $district,
                    type: $isDistrictHeadquarters ? 'city' : 'town',
                    priority: $isDistrictHeadquarters ? 80 : 55,
                    featuredSlugs: $data['featured'],
                );

                match ($result) {
                    'created' => $created++,
                    'updated' => $updated++,
                    default => null,
                };
            }
        }

        foreach ($data['villages'] ?? [] as $district => $villages) {
            foreach ($villages as $name) {
                $result = $this->syncCity(
                    state: $state,
                    name: $name,
                    district: $district,
                    type: 'village',
                    priority: 40,
                    featuredSlugs: $data['featured'],
                );

                match ($result) {
                    'created' => $created++,
                    'updated' => $updated++,
                    default => null,
                };
            }
        }

        $this->command?->info("Uttar Pradesh: {$created} naye shehar add hue, {$updated} update hue.");

        if ($this->skipped !== []) {
            $this->command?->warn('Ye naam duplicate the, isliye skip kiye gaye: '.implode(', ', $this->skipped));
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function syncState(array $attributes): State
    {
        return State::query()->updateOrCreate(
            ['slug' => $attributes['slug']],
            $attributes,
        );
    }

    /**
     * @param  list<string>  $featuredSlugs
     * @return 'created'|'updated'|'skipped'
     */
    private function syncCity(
        State $state,
        string $name,
        string $district,
        string $type,
        int $priority,
        array $featuredSlugs,
    ): string {
        $slug = $this->uniqueSlug($name, $district);

        if ($slug === null) {
            $this->skipped[] = $name;

            return 'skipped';
        }

        $isFeatured = in_array($slug, $featuredSlugs, true);

        $city = City::query()->where('state_id', $state->id)->where('slug', $slug)->first();

        if ($city) {
            // Admin ka visibility/featured decision safe rakhte hain — sirf basic detail update karte hain.
            $city->update([
                'name' => $name,
                'district' => $district,
            ]);

            return 'updated';
        }

        City::query()->create([
            'state_id' => $state->id,
            'name' => $name,
            'slug' => $slug,
            'type' => $type,
            'district' => $district,
            'is_visible' => true,
            'is_featured' => $isFeatured,
            'priority' => $isFeatured ? 95 : $priority,
            'sort_order' => $isFeatured ? 0 : 50,
        ]);

        return 'created';
    }

    /**
     * Ek hi state me ek hi naam ke do shehar ho sakte hain (jaise Phulpur, Ramnagar).
     * Aisi soorat me slug ke aage district lagakar unique bana dete hain:
     * "phulpur" aur "phulpur-prayagraj".
     */
    private function uniqueSlug(string $name, string $district): ?string
    {
        $base = Str::slug($name);

        if ($base === '') {
            return null;
        }

        $candidates = [$base, $base.'-'.Str::slug($district)];

        foreach ($candidates as $candidate) {
            if (! isset($this->usedSlugs[$candidate])) {
                $this->usedSlugs[$candidate] = $name;

                return $candidate;
            }
        }

        // Bahut rare: teesra same naam. Aage number lagaa dete hain.
        for ($i = 2; $i <= 20; $i++) {
            $candidate = $base.'-'.Str::slug($district).'-'.$i;

            if (! isset($this->usedSlugs[$candidate])) {
                $this->usedSlugs[$candidate] = $name;

                return $candidate;
            }
        }

        return null;
    }
}
