<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Support\SeoManager;
use Illuminate\Contracts\View\View;

/**
 * Blog listing, category archive and article pages.
 *
 *   /blogs                      -> listing (designed page + published posts)
 *   /blogs/category/{slug}      -> category archive
 *   /blogs/{slug}               -> article
 */
class BlogController extends Controller
{
    public function __construct(private readonly SeoManager $seo) {}

    public function index(): View
    {
        $page = Page::query()->where('slug', 'blogs')->first();

        abort_unless($page && $page->isPublished(), 404);

        return view('client.pages.blogs', [
            'page' => $page,
            'posts' => Post::query()->published()->ordered()->paginate(9),
            'categories' => PostCategory::query()->published()->ordered()->get(),
            'seo' => $this->seo->forModel($page, [
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => $page->name, 'url' => $page->url()],
                ],
                'faqs' => $page->faqs ?? [],
            ]),
        ]);
    }

    public function category(string $slug): View
    {
        $category = PostCategory::query()->published()->where('slug', $slug)->firstOrFail();

        $page = Page::query()->where('slug', 'blogs')->first();

        abort_unless($page && $page->isPublished(), 404);

        return view('client.pages.blogs', [
            'page' => $page,
            'category' => $category,
            'posts' => $category->posts()->published()->ordered()->paginate(9),
            'categories' => PostCategory::query()->published()->ordered()->get(),
            'seo' => $this->seo->forModel($category, [
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => $page->name, 'url' => $page->url()],
                    ['name' => $category->name, 'url' => $category->url()],
                ],
            ]),
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->published()->where('slug', $slug)->firstOrFail();

        $related = Post::query()
            ->published()
            ->whereKeyNot($post->getKey())
            ->when($post->post_category_id, fn ($query) => $query->where('post_category_id', $post->post_category_id))
            ->ordered()
            ->limit(3)
            ->get();

        return view('client.pages.blog-detail', [
            'post' => $post,
            'relatedPosts' => $related,
            'seo' => $this->seo->forModel($post, [
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => 'Blog', 'url' => url('/blogs')],
                    ['name' => $post->title, 'url' => $post->url()],
                ],
                'type' => 'article',
                'schema' => [$this->articleSchema($post)],
            ]),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function articleSchema(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->seoDescription(),
            'image' => $post->imageUrl(),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => $post->author ?: config('app.name'),
            ],
            'publisher' => ['@id' => url('/').'#business'],
            'mainEntityOfPage' => $post->url(),
            'url' => $post->url(),
        ];
    }
}
