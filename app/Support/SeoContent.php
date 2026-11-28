<?php

namespace App\Support;

use App\Models\City;
use App\Models\Locality;
use App\Models\SeoSetting;
use App\Models\Service;
use App\Models\State;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * SEO package for location pages (state / city / locality / service-in-city).
 *
 * IMPORTANT: this class never invents marketing copy. Headings, intros, about
 * text, highlights and FAQs come from the database only. When the admin has not
 * written something, the value is null/empty and the Blade component simply
 * skips that element (and the admin panel shows a "content missing" warning).
 *
 * What this class does add is machine-generated, factual metadata: canonical
 * URL, robots, breadcrumbs and JSON-LD structured data built from real business
 * settings.
 */
final class SeoContent
{
    public function __construct(
        private readonly SiteProfile $profile,
        private readonly SeoManager $seo,
    ) {}

    /**
     * State landing page.
     *
     * @param  Collection<int, City>  $cities
     * @return array<string, mixed>
     */
    public function state(State $state, Collection $cities): array
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $state->name, 'url' => $state->url()],
        ];

        $faqs = $this->faqs($state->faqs ?? []);

        return $this->seo->build([
            'title' => $state->seoTitle(),
            'description' => $state->seoDescription(),
            'canonical' => $state->seoCanonical(),
            'robots' => $state->seoRobots(),
            'image' => $state->ogImageUrl(),
            'image_alt' => $state->seoImageAlt(),
            'keywords' => $state->meta_keywords,
            'og_title' => $state->og_title,
            'og_description' => $state->og_description,
            'og_image' => $state->og_image,
            'twitter_card' => $state->twitter_card,
            'twitter_title' => $state->twitter_title,
            'twitter_description' => $state->twitter_description,
            'twitter_image' => $state->twitter_image,
            'heading' => $state->heading,
            'eyebrow' => $state->eyebrow,
            'sub_heading' => $state->sub_heading,
            'intro' => $state->intro_content,
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $faqs,
            'schema' => [
                $this->localBusinessSchema($cities),
                $this->itemListSchema($state->name.' service areas', $cities),
            ],
        ]) + [
            'about' => $state->about_content,
            'highlights' => array_values($state->highlights ?? []),
            'gallery' => $state->galleryItems(9),
        ];
    }

    /**
     * City landing page.
     *
     * @param  Collection<int, City>  $nearby
     * @return array<string, mixed>
     */
    public function city(State $state, City $city, Collection $nearby): array
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $state->name, 'url' => $state->url()],
            ['name' => $city->name, 'url' => $city->url()],
        ];

        $faqs = $this->faqs($city->faqs ?? []);

        $description = $city->seoDescription();

        if ($description === '') {
            $phone = $this->profile->phone();
            $description = Str::limit(
                'Wedding and event decoration in '.$city->name.', '.$state->name
                .' by '.$this->profile->name().'.'
                .($phone !== '' ? ' Call '.$phone.' for booking.' : ''),
                158,
            );
        }

        return $this->seo->build([
            'title' => $city->seoTitle(),
            'description' => $description,
            'canonical' => $city->seoCanonical(),
            'robots' => $city->seoRobots(),
            'image' => $city->ogImageUrl(),
            'image_alt' => $city->seoImageAlt(),
            'keywords' => $city->meta_keywords,
            'og_title' => $city->og_title,
            'og_description' => $city->og_description,
            'og_image' => $city->og_image,
            'twitter_card' => $city->twitter_card,
            'twitter_title' => $city->twitter_title,
            'twitter_description' => $city->twitter_description,
            'twitter_image' => $city->twitter_image,
            'heading' => $city->heading,
            'eyebrow' => $city->eyebrow,
            'sub_heading' => $city->sub_heading,
            'intro' => $city->intro_content,
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $faqs,
            'schema' => [
                $this->localBusinessSchema($nearby->prepend($city)),
            ],
        ]) + [
            'about' => $city->about_content,
            'highlights' => array_values($city->highlights ?? []),
            'gallery' => $city->galleryItems(9),
        ];
    }

    /**
     * Locality landing page: /{state}/{city}/{locality}.
     *
     * @param  Collection<int, Locality>  $nearby
     * @return array<string, mixed>
     */
    public function locality(State $state, City $city, Locality $locality, Collection $nearby): array
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $state->name, 'url' => $state->url()],
            ['name' => $city->name, 'url' => $city->url()],
            ['name' => $locality->name, 'url' => $locality->url()],
        ];

        $faqs = $this->faqs($locality->faqs ?? []);

        $description = $locality->seoDescription();

        // The admin may not have written a description for a small area yet.
        // A factual fallback still gives Google something useful to show.
        if ($description === '') {
            $phone = $this->profile->phone();
            $description = Str::limit(
                $locality->name.' in '.$city->name.', '.$state->name
                .' - wedding and event decoration by '.$this->profile->name().'.'
                .($phone !== '' ? ' Call '.$phone.' for booking.' : ''),
                158,
            );
        }

        return $this->seo->build([
            'title' => $locality->seoTitle(),
            'description' => $description,
            'canonical' => $locality->seoCanonical(),
            'robots' => $locality->seoRobots(),
            'image' => $locality->ogImageUrl(),
            'image_alt' => $locality->seoImageAlt(),
            'keywords' => $locality->meta_keywords,
            'og_title' => $locality->og_title,
            'og_description' => $locality->og_description,
            'og_image' => $locality->og_image,
            'twitter_card' => $locality->twitter_card,
            'twitter_title' => $locality->twitter_title,
            'twitter_description' => $locality->twitter_description,
            'twitter_image' => $locality->twitter_image,
            'heading' => $locality->heading,
            'eyebrow' => $locality->eyebrow,
            'sub_heading' => $locality->sub_heading,
            'intro' => $locality->intro_content,
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $faqs,
            'schema' => [
                $this->localBusinessSchema(collect([$city])),
            ],
        ]) + [
            'about' => $locality->about_content,
            'highlights' => array_values($locality->highlights ?? []),
            'gallery' => $locality->galleryItems(9),
        ];
    }

    /**
     * "{service} in {city}" landing page.
     *
     * @param  Collection<int, City>  $nearby
     * @return array<string, mixed>
     */
    public function cityService(State $state, City $city, Service $service, Collection $nearby): array
    {
        $serviceName = $service->short_name ?: $service->name;
        $url = $city->serviceUrl($service);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $state->name, 'url' => $state->url()],
            ['name' => $city->name, 'url' => $city->url()],
            ['name' => $serviceName, 'url' => $url],
        ];

        // Service-in-city pages inherit the service FAQs. City overrides win
        // when the admin has written them for this city.
        $faqs = $this->faqs($city->faqs ?: $service->contentValue('faqs', []));

        // Every service in a city must have its own title and description.
        // Reusing the city meta here made all 13 service pages of a city
        // identical in Google's eyes.
        $siteName = SeoSetting::value('site_title', $this->profile->name());
        $title = $serviceName.' in '.$city->name.' | '.$siteName;

        $lead = $serviceName.' in '.$city->name.', '.$state->name.'.';
        $source = trim((string) ($service->seoDescription() ?: $service->tagline ?: ''));

        $description = $source !== ''
            ? Str::limit($lead.' '.$source, 158)
            : Str::limit($lead.' Wedding and event decoration by '.$this->profile->name().'.', 158);

        return $this->seo->build([
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => $city->seoRobots(),
            'image' => $service->ogImageUrl(),
            'image_alt' => $service->seoImageAlt(),
            'keywords' => $city->meta_keywords,
            'og_title' => $title,
            'og_description' => $description,
            'og_image' => $city->og_image,
            'twitter_card' => $city->twitter_card,
            'twitter_title' => $title,
            'twitter_description' => $description,
            'twitter_image' => $city->twitter_image,
            'heading' => null,
            'sub_heading' => null,
            'intro' => $service->contentValue('city_intro'),
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $faqs,
            'schema' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $serviceName.' in '.$city->name,
                    'serviceType' => $service->name,
                    'description' => $service->seoDescription(),
                    'provider' => ['@id' => url('/').'#business'],
                    'image' => $service->imageUrl(),
                    'areaServed' => [
                        '@type' => 'City',
                        'name' => $city->name,
                        'containedInPlace' => ['@type' => 'State', 'name' => $state->name],
                    ],
                    'url' => $url,
                ],
            ],
        ]) + [
            'about' => $service->contentValue('city_about'),
            'highlights' => array_values($service->contentValue('highlights', []) ?? []),
            'gallery' => $service->galleryItems(9),
        ];
    }

    /**
     * Standalone service landing page: /services/{slug}
     *
     * @param  Collection<int, City>  $cities
     * @return array<string, mixed>
     */
    public function service(Service $service, Collection $cities): array
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Services', 'url' => url('services')],
            ['name' => $service->name, 'url' => $service->url()],
        ];

        $faqs = $this->faqs($service->contentValue('faqs', []));

        return $this->seo->build([
            'title' => $service->seoTitle(),
            'description' => $service->seoDescription(),
            'canonical' => $service->seoCanonical(),
            'robots' => $service->seoRobots(),
            'image' => $service->ogImageUrl(),
            'image_alt' => $service->seoImageAlt(),
            'keywords' => $service->meta_keywords,
            'og_title' => $service->og_title,
            'og_description' => $service->og_description,
            'og_image' => $service->og_image,
            'twitter_card' => $service->twitter_card,
            'twitter_title' => $service->twitter_title,
            'twitter_description' => $service->twitter_description,
            'twitter_image' => $service->twitter_image,
            'heading' => $service->contentValue('heading'),
            'eyebrow' => $service->eyebrow,
            'sub_heading' => $service->contentValue('sub_heading'),
            'intro' => $service->description ?: $service->tagline,
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $faqs,
            'schema' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $service->name,
                    'serviceType' => $service->name,
                    'description' => $service->seoDescription(),
                    'provider' => ['@id' => url('/').'#business'],
                    'image' => $service->imageUrl(),
                    'url' => $service->url(),
                ],
                $this->itemListSchema('Service areas', $cities),
            ],
        ]) + [
            'about' => $service->long_description ?: $service->contentValue('about'),
            'highlights' => array_values($service->contentValue('highlights', []) ?? []),
            'gallery' => $service->galleryItems(9),
        ];
    }

    /**
     * @param  array<int, mixed>  $faqs
     * @return list<array{question: string, answer: string, category: string}>
     */
    private function faqs(array $faqs): array
    {
        return collect($faqs)
            ->filter(fn (mixed $faq): bool => is_array($faq) && filled($faq['question'] ?? null))
            ->map(fn (array $faq): array => [
                'question' => (string) $faq['question'],
                'answer' => (string) ($faq['answer'] ?? ''),
                'category' => (string) ($faq['category'] ?? 'general'),
            ])
            ->values()
            ->all();
    }

    /**
     * LocalBusiness schema built from the real business settings.
     *
     * @param  Collection<int, City>  $cities
     * @return array<string, mixed>
     */
    private function localBusinessSchema(Collection $cities): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => url('/').'#localbusiness',
            'parentOrganization' => ['@id' => url('/').'#business'],
            'name' => $this->profile->name(),
            'url' => url('/'),
            'telephone' => $this->profile->phone(),
            'email' => $this->profile->email(),
            'image' => $this->profile->logo(),
            'description' => $this->profile->tagline(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->profile->address(),
                'addressLocality' => $this->profile->value('address_locality'),
                'addressRegion' => $this->profile->value('address_region'),
                'postalCode' => $this->profile->value('postal_code'),
                'addressCountry' => $this->profile->value('address_country') ?: 'IN',
            ],
            'areaServed' => $cities->pluck('name')->filter()->unique()->values()->all(),
        ];

        if ($priceRange = $this->profile->value('price_range')) {
            $schema['priceRange'] = $priceRange;
        }

        if ($hours = $this->profile->openingHours()) {
            $schema['openingHours'] = $hours;
        }

        if ($social = $this->profile->social()) {
            $schema['sameAs'] = array_values($social);
        }

        return $schema;
    }

    /**
     * @param  Collection<int, City>  $cities
     * @return array<string, mixed>
     */
    private function itemListSchema(string $name, Collection $cities): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $name,
            'itemListElement' => $cities->values()->take(100)->map(fn (City $city, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $city->name,
                'url' => $city->url(),
            ])->all(),
        ];
    }
}
