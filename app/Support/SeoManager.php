<?php

namespace App\Support;

use App\Models\SeoSetting;
use Illuminate\Database\Eloquent\Model;

/**
 * Single source of truth for page metadata.
 *
 * Every public page (page, state, city, locality, service, package, blog post,
 * category, home) builds its <head> data through this class, so there is
 * exactly one <title>, one canonical, one description and one set of Open
 * Graph / Twitter tags per response.
 *
 * Missing values stay empty - no marketing copy is invented here.
 */
final class SeoManager
{
    public function __construct(private readonly SiteProfile $profile) {}

    /**
     * Build the SEO payload for a public model.
     *
     * @param  array<string, mixed>  $options  title, description, image, image_alt, type,
     *                                         keywords, breadcrumbs, schema, canonical, robots
     * @return array<string, mixed>
     */
    public function forModel(Model $model, array $options = []): array
    {
        $title = $options['title']
            ?? (method_exists($model, 'seoTitle') ? $model->seoTitle() : null);

        $description = $options['description']
            ?? (method_exists($model, 'seoDescription') ? $model->seoDescription() : null);

        $canonical = $options['canonical']
            ?? (method_exists($model, 'seoCanonical') ? $model->seoCanonical() : null);

        $robots = $options['robots']
            ?? (method_exists($model, 'seoRobots') ? $model->seoRobots() : $this->defaultRobots());

        $image = $options['image']
            ?? (method_exists($model, 'ogImageUrl') ? $model->ogImageUrl() : null);

        $imageAlt = $options['image_alt']
            ?? (method_exists($model, 'seoImageAlt') ? $model->seoImageAlt() : ($title ?? ''));

        $type = $options['type']
            ?? (method_exists($model, 'seoOgType') ? $model->seoOgType() : 'website');

        $keywords = $options['keywords']
            ?? ($model->getAttribute('meta_keywords') ?: null);

        return $this->build([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $robots,
            'image' => $image,
            'image_alt' => $imageAlt,
            'type' => $type,
            'keywords' => $keywords,
            'og_title' => $options['og_title'] ?? $model->getAttribute('og_title'),
            'og_description' => $options['og_description'] ?? $model->getAttribute('og_description'),
            'og_image' => $options['og_image'] ?? $model->getAttribute('og_image'),
            'twitter_card' => $options['twitter_card'] ?? $model->getAttribute('twitter_card'),
            'twitter_title' => $options['twitter_title'] ?? $model->getAttribute('twitter_title'),
            'twitter_description' => $options['twitter_description'] ?? $model->getAttribute('twitter_description'),
            'twitter_image' => $options['twitter_image'] ?? $model->getAttribute('twitter_image'),
            'breadcrumbs' => $options['breadcrumbs'] ?? [],
            'schema' => $options['schema'] ?? [],
            'faqs' => $options['faqs'] ?? [],
            'heading' => $options['heading'] ?? $model->getAttribute('heading'),
            'sub_heading' => $options['sub_heading'] ?? $model->getAttribute('sub_heading'),
            'eyebrow' => $options['eyebrow'] ?? $model->getAttribute('eyebrow'),
            'intro' => $options['intro'] ?? $model->getAttribute('intro_content'),
        ]);
    }

    /**
     * Low level builder shared by every page type.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function build(array $data): array
    {
        $title = (string) ($data['title'] ?? '');
        $description = (string) ($data['description'] ?? '');
        $canonical = (string) ($data['canonical'] ?? url()->current());
        $image = (string) (MediaLibrary::url($data['image'] ?? null) ?: $this->profile->ogImage());
        $type = (string) ($data['type'] ?? 'website');
        $dimensions = MediaLibrary::dimensions($image);
        $ogImage = (string) (MediaLibrary::url($data['og_image'] ?? null) ?: $image);
        $ogDimensions = MediaLibrary::dimensions($ogImage);
        $siteName = (string) SeoSetting::value('site_title', $this->profile->name());

        $breadcrumbs = $data['breadcrumbs'] ?? [];
        $faqs = $this->normaliseFaqs($data['faqs'] ?? []);

        $schema = array_values(array_filter([
            $this->webPageSchema($canonical, $title, $description),
            $this->breadcrumbSchema($breadcrumbs),
            $this->faqSchema($faqs),
            ...($data['schema'] ?? []),
        ]));

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => (string) ($data['keywords'] ?? ''),
            'canonical' => $canonical,
            'robots' => (string) ($data['robots'] ?? $this->defaultRobots()),
            'type' => $type,
            'image' => $image,
            'image_alt' => (string) ($data['image_alt'] ?? ''),
            'image_width' => $dimensions['width'] ?? null,
            'image_height' => $dimensions['height'] ?? null,
            'og_image_width' => $ogDimensions['width'] ?? null,
            'og_image_height' => $ogDimensions['height'] ?? null,

            'og' => [
                'title' => (string) (! empty($data['og_title']) ? $data['og_title'] : $title),
                'description' => (string) (! empty($data['og_description']) ? $data['og_description'] : $description),
                'url' => $canonical,
                'image' => $ogImage,
                'type' => $type,
                'site_name' => $siteName,
            ],

            'twitter' => [
                'card' => (string) (! empty($data['twitter_card']) ? $data['twitter_card'] : SeoSetting::value('twitter_card', 'summary_large_image')),
                'title' => (string) (! empty($data['twitter_title']) ? $data['twitter_title'] : $title),
                'description' => (string) (! empty($data['twitter_description']) ? $data['twitter_description'] : $description),
                'image' => (string) (MediaLibrary::url($data['twitter_image'] ?? null) ?: $image),
            ],

            'breadcrumbs' => $breadcrumbs,
            'faqs' => $faqs,
            'heading' => $data['heading'] ?? null,
            'sub_heading' => $data['sub_heading'] ?? null,
            'eyebrow' => $data['eyebrow'] ?? null,
            'intro' => $data['intro'] ?? null,
            'schema' => $schema,
        ];
    }

    /**
     * Breadcrumb trail helper.
     *
     * @param  list<array{name: string, url: string}>  $crumbs
     * @return list<array{name: string, url: string}>
     */
    public function crumbs(array $crumbs): array
    {
        return $crumbs;
    }

    public function defaultRobots(): string
    {
        $index = SeoSetting::value('default_robots_index', '1') === '1' ? 'index' : 'noindex';
        $follow = SeoSetting::value('default_robots_follow', '1') === '1' ? 'follow' : 'nofollow';

        return $index.', '.$follow;
    }

    /**
     * @param  array<int, mixed>  $faqs
     * @return list<array{question: string, answer: string}>
     */
    private function normaliseFaqs(array $faqs): array
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
     * @return array<string, mixed>
     */
    private function webPageSchema(string $url, string $title, string $description): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $url.'#webpage',
            'url' => $url,
            'name' => $title,
            'description' => $description,
            'inLanguage' => 'en-IN',
            'isPartOf' => ['@id' => url('/').'#website'],
            'about' => ['@id' => url('/').'#business'],
        ];
    }

    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @return array<string, mixed>|null
     */
    private function breadcrumbSchema(array $breadcrumbs): ?array
    {
        if (count($breadcrumbs) < 2) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($breadcrumbs)
                ->values()
                ->map(fn (array $item, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ])
                ->all(),
        ];
    }

    /**
     * FAQ rich result - only for FAQs that are actually rendered on the page.
     *
     * @param  list<array{question: string, answer: string}>  $faqs
     * @return array<string, mixed>|null
     */
    private function faqSchema(array $faqs): ?array
    {
        if ($faqs === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)
                ->map(fn (array $faq): array => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ])
                ->all(),
        ];
    }
}
