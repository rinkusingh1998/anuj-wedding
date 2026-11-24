<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\Testimonial;
use App\Support\SeoManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * Static / designed website pages (Home, About Us, Contact Us, Gallery, ...).
 *
 * Every page must exist in the `pages` table. Page-level SEO, hero text,
 * images, FAQs, custom content sections and the optional "replace template"
 * mode all come from the database. The designed sections themselves keep their
 * original layout; their editable labels and empty states are exposed through
 * Global Settings and the page content blocks.
 *
 * A page that is hidden (or not created yet) returns 404, except the home page
 * which always renders but is marked noindex. Signed-in admins can preview a
 * hidden page with ?preview=1.
 */
class PageController extends Controller
{
    /**
     * Detail templates are rendered by their own controllers (blog posts,
     * services and packages). They must never be served as standalone pages.
     *
     * @var list<string>
     */
    private const TEMPLATE_ONLY_SLUGS = [
        'blog-detail',
        'service-detail',
        'package-detail',
    ];

    /**
     * Template key (pages.template) => view that renders it.
     *
     * A page created in the admin panel never needs a new Blade file: the
     * designed template is chosen here and any custom writing is added through
     * the page's own hero, content sections, blocks and FAQs.
     *
     * @var array<string, string>
     */
    private const TEMPLATE_VIEWS = [
        'home' => 'client.pages.home',
        'about' => 'client.pages.about-us',
        'contact' => 'client.pages.contact-us',
        'gallery' => 'client.pages.gallery',
        'packages' => 'client.pages.packages',
        'blogs' => 'client.pages.blogs',
        'faq' => 'client.pages.faq',
        'legal' => 'client.pages.default',
        'guide' => 'client.pages.wedding-event-decoration-guide',
        'services' => 'client.pages.services',
        'locations' => 'client.pages.locations',
        'sitemap' => 'client.pages.sitemap',
        'default' => 'client.pages.default',
    ];

    public function __construct(private readonly SeoManager $seo) {}

    public function home(): View
    {
        $page = Page::query()->where('slug', 'home')->firstOrFail();

        return view('client.pages.home', [
            ...$this->seoData($page),
            'services' => Service::query()->visible()->ordered()->get(),
            'testimonials' => Testimonial::query()->visible()->ordered()->get(),
            'posts' => Post::query()->published()->ordered()->limit(3)->get(),
            'packages' => Package::query()->published()->ordered()->get(),
        ]);
    }

    /**
     * Render a designed page by slug.
     */
    public function allpages(string $page): View
    {
        abort_unless(preg_match('/^[a-z0-9\-]+$/', $page) === 1, 404);
        abort_if(in_array($page, self::TEMPLATE_ONLY_SLUGS, true), 404);

        $model = Page::query()->where('slug', $page)->first();

        // A page that is not published is not public at all. Signed-in admins
        // can still preview it with ?preview=1 before switching it live.
        $isPreview = request()->boolean('preview') && auth()->check();

        abort_unless($model && ($model->isPublished() || $isPreview), 404);

        return view($this->viewFor($page, $model), [
            ...$this->seoData($model),
            ...$this->pageData($model),
        ]);
    }

    /**
     * Pick the view for a page.
     *
     * 1. A designed page with its own Blade file keeps its original design.
     * 2. Otherwise the template selected in the admin panel is used.
     * 3. The generic page template is the safe fallback, so a brand new page
     *    always renders without any code change.
     */
    private function viewFor(string $slug, Page $page): string
    {
        $slugView = 'client.pages.'.$slug;

        if (view()->exists($slugView)) {
            return $slugView;
        }

        $templateView = self::TEMPLATE_VIEWS[$page->template ?? 'default'] ?? null;

        if ($templateView && view()->exists($templateView)) {
            return $templateView;
        }

        return 'client.pages.default';
    }

    /**
     * Extra collections a designed page may render.
     *
     * @return array<string, mixed>
     */
    private function pageData(Page $page): array
    {
        $data = [
            'services' => Service::query()->visible()->ordered()->get(),
        ];

        $key = $page->slug;

        $template = (string) ($page->template ?? 'default');

        if (! in_array($key, ['packages', 'blogs', 'faq', 'gallery', 'contact-us', 'about-us'], true)) {
            $key = match ($template) {
                'packages' => 'packages',
                'blogs' => 'blogs',
                'faq' => 'faq',
                'gallery' => 'gallery',
                'contact' => 'contact-us',
                'about' => 'about-us',
                'home' => 'home',
                default => $key,
            };
        }

        return $data + match ($key) {
            'home' => [
                'testimonials' => Testimonial::query()->visible()->ordered()->get(),
                'posts' => Post::query()->published()->ordered()->limit(3)->get(),
            ],
            'packages' => ['packages' => Package::query()->published()->ordered()->get()],
            'blogs' => [
                'posts' => Post::query()->published()->ordered()->paginate(9),
                'categories' => PostCategory::query()->published()->ordered()->get(),
            ],
            'faq' => ['faqs' => Faq::query()->visible()->ordered()->get()],
            'gallery' => ['galleryItems' => $page->galleryItems(24)],
            'contact-us', 'about-us' => [
                'testimonials' => Testimonial::query()->visible()->ordered()->get(),
            ],
            default => [],
        };
    }

    /**
     * @return array{seo: array<string, mixed>, page: Page}
     */
    private function seoData(Page $page): array
    {
        $breadcrumbs = $page->slug === 'home'
            ? [['name' => 'Home', 'url' => url('/')]]
            : [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => $page->name, 'url' => $page->url()],
            ];

        $options = [
            'breadcrumbs' => $breadcrumbs,
            'faqs' => $page->faqs ?? [],
            'type' => 'website',
        ];

        if (request()->boolean('preview')) {
            $options['robots'] = 'noindex, nofollow';
        }

        return [
            'seo' => $this->seo->forModel($page, $options),
            'page' => $page,
        ];
    }

    /**
     * Fallback used by the catch-all route for one-segment URLs that are not a
     * state. Kept for backwards compatibility with the old router.
     */
    public function resolveSlug(string $slug): View
    {
        return $this->allpages(Str::slug($slug));
    }
}
