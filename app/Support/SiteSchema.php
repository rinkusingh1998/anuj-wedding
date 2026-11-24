<?php

namespace App\Support;

use App\Models\City;
use App\Models\Faq;
use App\Models\Page;
use App\Models\SeoSetting;
use App\Models\Service;

/**
 * Site-wide JSON-LD (Organization / LocalBusiness, WebSite, service catalogue).
 *
 * All values come from admin managed business settings and database content.
 * No review, rating or address is invented.
 */
final class SiteSchema
{
    public function __construct(private readonly SiteProfile $profile) {}

    public function json(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                $this->business(),
                $this->website(),
                $this->serviceList(),
            ])),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * FAQPage JSON-LD for the home page, built from the home page FAQs.
     */
    public function homeFaqJson(): ?string
    {
        $page = Page::query()->where('slug', 'home')->first();
        $faqs = $page?->faqs ?? [];

        if ($faqs === []) {
            $faqs = Faq::query()->visible()->ordered()->limit(10)->get()
                ->map(fn (Faq $faq): array => $faq->toFaqArray())
                ->all();
        }

        $faqs = collect($faqs)->filter(fn ($faq): bool => is_array($faq) && filled($faq['question'] ?? null));

        if ($faqs->isEmpty()) {
            return null;
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => (string) $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => (string) ($faq['answer'] ?? ''),
                ],
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @return array<string, mixed>
     */
    private function business(): array
    {
        $schema = [
            '@type' => (string) SeoSetting::value('business_type', 'LocalBusiness'),
            '@id' => url('/').'#business',
            'name' => $this->profile->name(),
            'url' => url('/'),
        ];

        if ($phone = $this->profile->phone()) {
            $schema['telephone'] = $phone;
        }

        if ($email = $this->profile->email()) {
            $schema['email'] = $email;
        }

        $schema['image'] = $this->profile->logo();
        $schema['logo'] = $this->profile->logo();

        if ($tagline = $this->profile->tagline()) {
            $schema['description'] = $tagline;
        }

        $address = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $this->profile->address(),
            'addressLocality' => $this->profile->value('address_locality'),
            'addressRegion' => $this->profile->value('address_region'),
            'postalCode' => $this->profile->value('postal_code'),
            'addressCountry' => $this->profile->value('address_country') ?: 'IN',
        ]);

        if (count($address) > 1) {
            $schema['address'] = $address;
        }

        if ($priceRange = $this->profile->priceRange()) {
            $schema['priceRange'] = $priceRange;
        }

        if ($hours = $this->profile->openingHours()) {
            $schema['openingHours'] = $hours;
        }

        if ($areaServed = $this->areaServed()) {
            $schema['areaServed'] = $areaServed;
        }

        if ($social = $this->profile->social()) {
            $schema['sameAs'] = array_values($social);
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    private function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => $this->profile->name(),
            'publisher' => ['@id' => url('/').'#business'],
            'inLanguage' => 'en-IN',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function serviceList(): ?array
    {
        $services = Service::query()->visible()->ordered()->get();

        if ($services->isEmpty()) {
            return null;
        }

        return [
            '@type' => 'OfferCatalog',
            'name' => 'Services',
            'itemListElement' => $services->map(fn (Service $service): array => [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => $service->name,
                    'description' => $service->tagline ?: $service->description,
                    'url' => $service->url(),
                ],
            ])->values()->all(),
        ];
    }

    /**
     * Active service areas only.
     *
     * @return list<string>
     */
    private function areaServed(): array
    {
        return City::query()
            ->published()
            ->serviceArea()
            ->ordered()
            ->pluck('name')
            ->values()
            ->all();
    }
}
