<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Faq;
use App\Models\Locality;
use App\Models\Package;
use App\Models\Post;
use App\Models\PostCategory;
use App\Support\MediaLibrary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the admin-managed content that is not a static page: packages, blog
 * categories and posts, FAQs and localities for the eight primary service
 * areas.
 *
 * Every record is created only when it does not exist yet, so admin edits are
 * never overwritten.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPackages();
        $this->seedBlog();
        $this->seedFaqs();
        $this->seedLocalities();
    }

    private function seedPackages(): void
    {
        $packages = [
            [
                'name' => 'Basic Celebration',
                'slug' => 'basic-celebration',
                'label' => 'Essential Decoration',
                'price_from' => null,
                'price_note' => 'Final quote depends on venue and requirements',
                'description' => 'An essential decoration setup for small home functions and intimate celebrations.',
                'features' => ['Essential decoration setup', 'Elegant theme options', 'Ideal for small functions', 'Professional setup team'],
                'is_featured' => false,
                'sort_order' => 10,
            ],
            [
                'name' => 'Premium Celebration',
                'slug' => 'premium-celebration',
                'label' => 'Customized Decoration',
                'badge' => 'Most Popular',
                'price_from' => null,
                'price_note' => 'Final quote depends on venue and requirements',
                'description' => 'A customised decoration concept with premium props for family functions and wedding rituals.',
                'features' => ['Customized decoration concept', 'Premium props & setups', 'Ideal for family functions', 'Dedicated setup team'],
                'is_featured' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'Royal Celebration',
                'slug' => 'royal-celebration',
                'label' => 'Luxury Event Styling',
                'price_from' => null,
                'price_note' => 'Final quote depends on venue and requirements',
                'description' => 'An exclusive styling package for weddings and grand celebrations.',
                'features' => ['Exclusive theme designs', 'Luxury decoration elements', 'Wedding-ready styling', 'Complete event decoration'],
                'is_featured' => false,
                'sort_order' => 30,
            ],
        ];

        foreach ($packages as $package) {
            Package::query()->updateOrCreate(
                ['slug' => $package['slug']],
                array_merge($package, [
                    'image' => MediaLibrary::image('packages'),
                    'og_image' => MediaLibrary::image('packages'),
                    'gallery' => [],
                    'content' => [],
                    'is_visible' => true,
                    'priority' => 60,
                    'published_at' => now(),
                    'meta_title' => $package['name'].' | Decoration Package',
                    'meta_description' => $package['description'],
                    'focus_keyword' => $package['name'],
                ]),
            );
        }
    }

    private function seedBlog(): void
    {
        $categories = [
            'haldi-decoration' => 'Haldi Decoration',
            'mehendi-decoration' => 'Mehendi Decoration',
            'balloon-decoration' => 'Balloon Decoration',
            'wedding-planning' => 'Wedding Planning',
        ];

        $categoryModels = [];

        foreach ($categories as $slug => $name) {
            $categoryModels[$slug] = PostCategory::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => null,
                    'is_visible' => true,
                    'sort_order' => 0,
                    'published_at' => now(),
                ],
            );
        }

        $posts = [
            [
                'category' => 'haldi-decoration',
                'title' => 'Haldi Decoration Ideas for a Colourful Function',
                'slug' => 'haldi-decoration-ideas',
                'excerpt' => 'Practical ideas for planning a colourful haldi setup with flowers, seating and a photo-friendly backdrop.',
                'body' => '<p>A haldi function is one of the most colourful parts of a wedding. Choose a clear focal point, comfortable seating for the family and a backdrop that photographs well. Marigold, fresh flowers and fabric drapes are a simple starting point.</p><p>Keep the decoration practical: leave enough space for guests to move, protect the flooring and confirm the setup time with the venue.</p>',
                'reading_minutes' => 4,
                'is_featured' => true,
            ],
            [
                'category' => 'mehendi-decoration',
                'title' => 'How to Plan a Mehendi Night Decoration',
                'slug' => 'plan-mehendi-night-decoration',
                'excerpt' => 'A simple planning checklist for mehendi night decoration, seating, lighting and a comfortable guest area.',
                'body' => '<p>Mehendi night works best when the decoration is comfortable and easy to use. Plan the seating first, then add lighting and a backdrop. Keep the mehendi artist area well lit and leave a clear path for guests.</p>',
                'reading_minutes' => 4,
                'is_featured' => true,
            ],
            [
                'category' => 'balloon-decoration',
                'title' => 'Balloon Decoration Ideas for Birthday Parties',
                'slug' => 'balloon-decoration-birthday-ideas',
                'excerpt' => 'Balloon arches, garlands and backdrop ideas for a birthday celebration at home or at a venue.',
                'body' => '<p>Balloon decoration can be as simple or as elaborate as you like. Start with one focal wall, choose a colour palette and add a few larger statement balloons. Confirm the ceiling height and whether the venue allows tape or fixing points.</p>',
                'reading_minutes' => 3,
                'is_featured' => false,
            ],
            [
                'category' => 'wedding-planning',
                'title' => 'Wedding Decoration Planning Checklist',
                'slug' => 'wedding-decoration-planning-checklist',
                'excerpt' => 'What to confirm before booking a wedding decoration team: venue, timings, theme, flowers and setup access.',
                'body' => '<p>Before booking a decoration team, confirm the venue, the setup and removal timings, what the venue already provides and which areas you want to decorate. Share reference photos and agree on the final scope in writing.</p>',
                'reading_minutes' => 5,
                'is_featured' => false,
            ],
        ];

        foreach ($posts as $index => $post) {
            Post::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'post_category_id' => $categoryModels[$post['category']]->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'author' => null,
                    'reading_minutes' => $post['reading_minutes'],
                    'image' => MediaLibrary::image('blogs'),
                    'og_image' => MediaLibrary::image('blogs'),
                    'gallery' => [],
                    'is_visible' => true,
                    'is_featured' => $post['is_featured'],
                    'priority' => 50,
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => now(),
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                    'focus_keyword' => Str::lower($post['title']),
                ],
            );
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['question' => 'Which areas do you serve?', 'answer' => 'We currently accept decoration bookings in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar (Noida and Greater Noida), Ghaziabad, Hapur, Meerut and Mathura.', 'category' => 'booking', 'scope' => 'global'],
            ['question' => 'How do I book a decoration date?', 'answer' => 'Share your event date, city, venue and requirements through the enquiry form, phone or WhatsApp. The team will confirm availability and share a quotation.', 'category' => 'booking', 'scope' => 'global'],
            ['question' => 'Can the theme and colours be customised?', 'answer' => 'Yes. The theme, colour palette, flowers, balloons, lighting and props are planned around your venue and preferences.', 'category' => 'decoration', 'scope' => 'global'],
            ['question' => 'Do you provide complete event decoration?', 'answer' => 'Yes. Complete event decoration can combine the stage, entrance, seating, counters, flowers and lighting in one coordinated setup.', 'category' => 'decoration', 'scope' => 'global'],
            ['question' => 'How is the decoration price decided?', 'answer' => 'The price depends on the venue size, number of decoration elements, flowers, lighting and setup time. A written quotation is shared after the requirements are confirmed.', 'category' => 'pricing', 'scope' => 'global'],
            ['question' => 'Do you take same-day bookings?', 'answer' => 'Same-day bookings depend on team availability. Contact the team as early as possible with the event date and venue.', 'category' => 'booking', 'scope' => 'global'],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, [
                    'is_visible' => true,
                    'sort_order' => ($index + 1) * 10,
                ]),
            );
        }
    }

    private function seedLocalities(): void
    {
        $areas = [
            'Agra' => ['Tajganj', 'Kamla Nagar', 'Dayalbagh', 'Sikandra'],
            'Aligarh' => ['Ramghat Road', 'Civil Lines', 'Dodhpur', 'Jamalpur'],
            'Bulandshahr' => ['Civil Lines', 'Kali Nadi Road', 'Bypass Road', 'Moti Bagh'],
            'Noida' => ['Sector 18', 'Sector 62', 'Sector 137', 'Noida Extension'],
            'Greater Noida' => ['Alpha I', 'Beta I', 'Gamma I', 'Omega I'],
            'Ghaziabad' => ['Indirapuram', 'Vaishali', 'Raj Nagar Extension', 'Kavi Nagar'],
            'Hapur' => ['Freeganj', 'Meerut Road', 'Railway Road', 'Garhmukteshwar Road'],
            'Mathura' => ['Vrindavan', 'Krishna Nagar', 'Govardhan', 'Mathura Refinery Nagar'],
            'Meerut' => ['Shastri Nagar', 'Sadar Bazaar', 'Pallavpuram', 'Modipuram'],
        ];

        foreach ($areas as $cityName => $localities) {
            $city = City::query()
                ->where('name', $cityName)
                ->where('is_service_area', true)
                ->first();

            if (! $city) {
                continue;
            }

            foreach ($localities as $index => $name) {
                Locality::query()->updateOrCreate(
                    [
                        'city_id' => $city->id,
                        'slug' => Str::slug($name),
                    ],
                    [
                        'state_id' => $city->state_id,
                        'name' => $name,
                        'type' => 'locality',
                        'is_visible' => true,
                        'is_featured' => $index === 0,
                        'is_service_area' => true,
                        'priority' => 50,
                        'sort_order' => ($index + 1) * 10,
                        'published_at' => now(),
                    ],
                );
            }
        }
    }
}
