<?php

namespace App\Support;

use App\Models\Page;
use App\Models\SeoSetting;
use Illuminate\Support\Str;

/**
 * Static pages (Home, About Us, Contact Us, Legal pages waghera) ka SEO package.
 *
 * Admin panel me jo bhi fields bhare honge wahi use hote hain. Jo khaali
 * hain unke liye site settings se ek theek-thak default title/description
 * ban jaata hai. Isi liye har page ka meta apna aur unique hota hai —
 * home page ka title kisi doosre page par repeat nahi hota.
 */
final class PageSeo
{
    public function __construct(private readonly SiteProfile $profile) {}

    /**
     * @return array<string, mixed>
     */
    public function build(Page $page): array
    {
        $name = $page->name;
        $title = $this->title($page);
        $description = $this->description($page);
        $canonical = $page->url();

        $breadcrumbs = $page->slug === 'home'
            ? [['name' => 'Home', 'url' => url('/')]]
            : [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => $name, 'url' => $canonical],
            ];

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $page->meta_keywords ?: SeoSetting::value('meta_keywords', ''),
            'canonical' => $canonical,
            'image' => $page->ogImageUrl(),
            'robots' => $page->robots(),
            'heading' => $page->heading,
            'sub_heading' => $page->sub_heading,
            'intro' => $page->intro_content,
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $page->faqs ?? [],
            'highlights' => $page->contentValue('hero_highlights', []),
            'schema' => [
                $this->webPageSchema($page, $title, $description),
                $this->breadcrumbSchema($breadcrumbs),
                ...$this->faqSchema($page->faqs ?? []),
            ],
        ];
    }

    /**
     * FAQ structured data (only when the page has FAQs).
     *
     * @param  list<array{question: string, answer: string}>  $faqs
     * @return list<array<string, mixed>>
     */
    private function faqSchema(array $faqs): array
    {
        if ($faqs === []) {
            return [];
        }

        return [[
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'] ?? '',
                ],
            ])->all(),
        ]];
    }

    private function title(Page $page): string
    {
        if (filled($page->meta_title)) {
            return (string) $page->meta_title;
        }

        if ($page->slug === 'home') {
            return SeoSetting::value('home_meta_title', $this->profile->name());
        }

        return Str::headline($page->name).' | '.SeoSetting::value('site_title', $this->profile->name());
    }

    private function description(Page $page): string
    {
        if (filled($page->meta_description)) {
            return (string) $page->meta_description;
        }

        if ($page->slug === 'home') {
            return SeoSetting::value('home_meta_description', '');
        }

        return $this->profile->name().' — '.Str::headline($page->name)
            .'. Wedding and event decoration services across our published service areas in Uttar Pradesh.'
            .' Call '.$this->profile->phone().' for details.';
    }

    /**
     * @return array<string, mixed>
     */
    private function webPageSchema(Page $page, string $title, string $description): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $page->url().'#webpage',
            'url' => $page->url(),
            'name' => $title,
            'description' => $description,
            'inLanguage' => 'en-IN',
            'isPartOf' => ['@id' => url('/').'#website'],
            'about' => ['@id' => url('/').'#business'],
            'breadcrumb' => ['@id' => $page->url().'#breadcrumb'],
        ];
    }

    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @return array<string, mixed>
     */
    private function breadcrumbSchema(array $breadcrumbs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            '@id' => (collect($breadcrumbs)->last()['url'] ?? url('/')).'#breadcrumb',
            'itemListElement' => collect($breadcrumbs)->values()->map(fn (array $item, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->all(),
        ];
    }
}
