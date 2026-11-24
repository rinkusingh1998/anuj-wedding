<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * The website service catalogue.
 *
 * Every service keeps its own landing page (/services/{slug}) and creates one
 * "{service} in {city}" page per active city, so removing a service here also
 * removes all of its city pages.
 *
 * Re-running is safe: services are matched by slug, so admin edits to text and
 * images are only overwritten with the values below when the seeder is run
 * intentionally (for example on a fresh install).
 */
class ServiceSeeder extends Seeder
{
    /**
     * Services that are no longer offered. They are soft deleted so their old
     * URLs stop working while the records stay recoverable from the admin panel.
     *
     * @var list<string>
     */
    private const RETIRED = [
        'flower-decoration',
        'stage-backdrop-decoration',
        'entry-decoration',
        'wedding-counters',
    ];

    /**
     * @var list<array{
     *     name: string,
     *     slug: string,
     *     short_name: string,
     *     tagline: string,
     *     icon: string,
     *     description: string,
     *     category: string,
     *     image: string,
     *     gallery: list<string>
     * }>
     */
    private const SERVICES = [
        [
            'name' => 'Wedding Decoration',
            'slug' => 'wedding-decoration',
            'short_name' => 'Wedding Decoration',
            'tagline' => 'Complete wedding stage, mandap and venue decoration',
            'icon' => 'fa-solid fa-ring',
            'description' => 'Complete wedding decoration — mandap, stage, seating, entrance and lighting — planned and executed by one team. We match your colours, theme and budget so the whole venue looks like a single celebration.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/wedding-decoration.png',
            'gallery' => [
                'assets/services/wedding-decoration.png',
                'assets/services/comp-event.png',
                'assets/services/mehendi-decoration.png',
                'assets/services/chunni-deco.png',
            ],
        ],
        [
            'name' => 'Engagement Decoration',
            'slug' => 'engagement-decoration',
            'short_name' => 'Engagement Decoration',
            'tagline' => 'Ring ceremony stage and engagement venue decoration',
            'icon' => 'fa-solid fa-hand-holding-heart',
            'description' => 'Elegant engagement decoration with a ring ceremony stage, floral backdrop, couple seating and entrance styling. Choose a pastel, royal or contemporary theme and we handle the complete setup.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/engagement-decoration.png',
            'gallery' => [
                'assets/services/engagement-decoration.png',
                'assets/services/wedding-decoration.png',
                'assets/services/comp-event.png',
            ],
        ],
        [
            'name' => 'Haldi Decoration',
            'slug' => 'haldi-decoration',
            'short_name' => 'Haldi Decoration',
            'tagline' => 'Yellow theme haldi decoration at home or hall',
            'icon' => 'fa-solid fa-sun',
            'description' => 'Yellow theme decoration for the haldi function with marigold flowers, umbrellas, matka props and comfortable family seating. We set it up at your home, terrace or banquet hall.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/haldi-decoration.png',
            'gallery' => [
                'assets/services/haldi-decoration.png',
                'assets/services/mehendi-decoration.png',
                'assets/services/wedding-decoration.png',
            ],
        ],
        [
            'name' => 'Mehendi Decoration',
            'slug' => 'mehendi-decoration',
            'short_name' => 'Mehendi Decoration',
            'tagline' => 'Colourful mehendi decoration with props and seating',
            'icon' => 'fa-solid fa-hand-sparkles',
            'description' => 'Colourful mehendi decoration with drapes, mirrors, a jhoola, cushions and comfortable seating for guests. Add marigold strings, bandhani prints and fairy lights for the perfect mehendi evening.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/mehendi-decoration.png',
            'gallery' => [
                'assets/services/mehendi-decoration.png',
                'assets/services/haldi-decoration.png',
                'assets/services/chunni-deco.png',
            ],
        ],
        [
            'name' => 'Chunni Ceremony Decoration',
            'slug' => 'chunni-ceremony',
            'short_name' => 'Chunni Ceremony',
            'tagline' => 'Chunni ceremony and rasam decoration',
            'icon' => 'fa-solid fa-gem',
            'description' => 'Decoration and stage setup for the chunni ceremony — from a simple family rasam to a royal themed stage. Flower work, drapes, seating and lighting are all included in one booking.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/chunni-deco.png',
            'gallery' => [
                'assets/services/chunni-deco.png',
                'assets/services/wedding-decoration.png',
                'assets/services/mehendi-decoration.png',
            ],
        ],
        [
            'name' => 'Family Function Decoration',
            'slug' => 'family-function-decoration',
            'short_name' => 'Family Function',
            'tagline' => 'Family function and private celebration decoration',
            'icon' => 'fa-solid fa-people-roof',
            'description' => 'Decoration for family functions and private celebrations — anniversaries, get-togethers, retirement parties and poojas. Stage, entrance, seating and lighting are arranged to suit the occasion and budget.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/family-function-decoration.png',
            'gallery' => [
                'assets/services/family-function-decoration.png',
                'assets/services/comp-event.png',
                'assets/services/birthday-deco.png',
            ],
        ],
        [
            'name' => 'Honeymoon Decoration',
            'slug' => 'honeymoon-decoration',
            'short_name' => 'Honeymoon Decoration',
            'tagline' => 'Romantic honeymoon room and couple stage decoration',
            'icon' => 'fa-solid fa-heart',
            'description' => 'A romantic honeymoon setup with a flower and balloon decorated bed, candle lighting, a couple stage and a welcome arrangement. Perfect for the first night, an anniversary or a surprise celebration.',
            'category' => 'wedding-functions',
            'image' => 'assets/services/honeymoon-decoration.png',
            'gallery' => [
                'assets/services/honeymoon-decoration.png',
                'assets/services/wedding-decoration.png',
                'assets/services/comp-event.png',
            ],
        ],
        [
            'name' => 'Balloon Decoration',
            'slug' => 'balloon-decoration',
            'short_name' => 'Balloon Decoration',
            'tagline' => 'Balloon decoration for weddings and birthdays',
            'icon' => 'fa-solid fa-circle',
            'description' => 'Balloon arches, balloon walls, ceiling balloons and theme-based balloon decoration for weddings, birthdays and welcome entries. We use fresh balloons in your chosen colours and finish the setup before guests arrive.',
            'category' => 'decor-styling',
            'image' => 'assets/services/ballon-deco.png',
            'gallery' => [
                'assets/services/ballon-deco.png',
                'assets/services/birthday-deco.png',
                'assets/services/engagement-decoration.png',
            ],
        ],
        [
            'name' => 'Fogg and Matka Entry Setup',
            'slug' => 'fogg-matka',
            'short_name' => 'Fogg & Matka',
            'tagline' => 'Fog effect and matka phod entry setup',
            'icon' => 'fa-solid fa-cloud',
            'description' => 'A complete fog machine, cold pyro and matka entry setup for the bride and groom entry. Our team handles the timing, safety and cleanup so the entry looks dramatic and runs smoothly.',
            'category' => 'decor-styling',
            'image' => 'assets/services/mattka-deco.png',
            'gallery' => [
                'assets/services/mattka-deco.png',
                'assets/services/wedding-decoration.png',
                'assets/services/comp-event.png',
            ],
        ],
        [
            'name' => 'Birthday Decoration',
            'slug' => 'birthday-decoration',
            'short_name' => 'Birthday Decoration',
            'tagline' => 'Birthday party decoration for kids and adults',
            'icon' => 'fa-solid fa-cake-candles',
            'description' => 'Theme decoration for birthday parties — balloons, backdrop, name cutout, cake table and lighting. Choose a cartoon, pastel or neon theme for a first birthday, kids party or adult celebration.',
            'category' => 'party-celebration',
            'image' => 'assets/services/birthday-deco.png',
            'gallery' => [
                'assets/services/birthday-deco.png',
                'assets/services/ballon-deco.png',
                'assets/services/comp-event.png',
            ],
        ],
        [
            'name' => 'Festival Decoration',
            'slug' => 'festival-decoration',
            'short_name' => 'Festival Decoration',
            'tagline' => 'Festival and pooja decoration for home and venue',
            'icon' => 'fa-solid fa-fire',
            'description' => 'Festival decoration for Diwali, Navratri, Ganesh Chaturthi and family poojas — entrance rangoli, flower garlands, lights and a decorated pooja area. Setup is done at your home, shop or venue.',
            'category' => 'party-celebration',
            'image' => 'assets/services/festival-decoration.png',
            'gallery' => [
                'assets/services/festival-decoration.png',
                'assets/services/haldi-decoration.png',
                'assets/services/mattka-deco.png',
            ],
        ],
        [
            'name' => 'Complete Event Decoration',
            'slug' => 'complete-event-decor',
            'short_name' => 'Complete Event Decoration',
            'tagline' => 'Full function decoration — from haldi to reception',
            'icon' => 'fa-solid fa-champagne-glasses',
            'description' => 'Decoration for the whole function in a single booking — haldi, mehendi, wedding and reception all included. One team, one theme and one price for every stage of the celebration.',
            'category' => 'party-celebration',
            'image' => 'assets/services/comp-event.png',
            'gallery' => [
                'assets/services/comp-event.png',
                'assets/services/wedding-decoration.png',
                'assets/services/haldi-decoration.png',
                'assets/services/mehendi-decoration.png',
            ],
        ],
    ];

    public function run(): void
    {
        $categories = ServiceCategory::query()
            ->pluck('id', 'slug');

        foreach (self::SERVICES as $index => $service) {
            $model = Service::withTrashed()->firstOrNew(['slug' => $service['slug']]);

            $model->fill([
                'service_category_id' => $categories[$service['category']] ?? null,
                'name' => $service['name'],
                'short_name' => $service['short_name'],
                'tagline' => $service['tagline'],
                'icon' => $service['icon'],
                'description' => $service['description'],
                'image' => $service['image'],
                'gallery' => $service['gallery'],
                'is_visible' => true,
                'priority' => 70,
                'sort_order' => ($index + 1) * 10,
            ]);

            $model->deleted_at = null;
            $model->save();
        }

        Service::withTrashed()
            ->whereIn('slug', self::RETIRED)
            ->update(['is_visible' => false]);

        Service::withTrashed()
            ->whereIn('slug', self::RETIRED)
            ->delete();

        $this->command?->info(count(self::SERVICES).' services ready.');
    }
}
