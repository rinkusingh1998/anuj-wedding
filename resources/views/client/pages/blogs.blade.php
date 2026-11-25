@extends('client.layout')

@php
    $blogPosts = collect($posts->items());
    $featuredPost = $blogPosts->firstWhere('is_featured', true) ?? $blogPosts->first();
    $latestPosts = $blogPosts
        ->reject(fn ($post) => $featuredPost && $post->is($featuredPost))
        ->take(8);
@endphp

@section('content')
<main class="blog-page">
    <section class="contact-hero-section" aria-labelledby="blogHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    @if (! empty($category))
                        <a href="{{ url('/blogs') }}">Blog</a>
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        <span aria-current="page">{{ $category->name }}</span>
                    @else
                        <span aria-current="page">Blog</span>
                    @endif
                </nav>

                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? 'Wedding & Event Decoration Journal' }}
                </span>

                <h1 id="blogHeroTitle">{!! $seo['heading'] ?? '' !!}</h1>

                @if (filled($seo['intro'] ?? null))
                    <p>{!! $seo['intro'] !!}</p>
                @endif

                <div class="contact-hero-actions">
                    <a href="#latestBlogs" class="contact-primary-btn">
                        Explore Our Blogs
                        <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}" class="contact-outline-btn">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        Plan Your Event
                    </a>
                </div>

                <div class="contact-hero-trust">
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Wedding Inspiration</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Decoration Planning Tips</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Local Event Ideas</span>
                </div>
            </div>
        </div>
    </section>

    @if (isset($categories) && $categories->isNotEmpty())
        <nav class="blog-category-bar" aria-label="Blog categories">
            <div class="container">
                <a href="{{ url('/blogs') }}" class="{{ empty($category) ? 'is-active' : '' }}">All Articles</a>
                @foreach ($categories as $blogCategory)
                    <a href="{{ $blogCategory->url() }}"
                       class="{{ isset($category) && $category->is($blogCategory) ? 'is-active' : '' }}">
                        {{ $blogCategory->name }}
                    </a>
                @endforeach
            </div>
        </nav>
    @endif

    <section class="blog-featured-section" aria-labelledby="featuredBlogTitle">
        <div class="container">
            <div class="section-heading section-heading-center">
                <span class="section-eyebrow">Featured Inspiration</span>
                <h2 class="section-title" id="featuredBlogTitle">
                    {{ $page->contentValue('copy.text_2') ?? 'Start With Our Latest Wedding Ideas' }}
                </h2>
                <p class="section-description">{{ $page->contentValue('copy.text_3') ?? '' }}</p>
            </div>

            @if ($featuredPost)
                <article class="blog-featured-card">
                    <div class="blog-featured-image">
                        <img src="{{ $featuredPost->imageUrl() }}"
                             alt="{{ $featuredPost->image_alt ?: $featuredPost->title }}"
                             loading="eager"
                             decoding="async">
                    </div>

                    <div class="blog-featured-content">
                        <div class="blog-featured-meta">
                            <span>{{ $featuredPost->category?->name ?: 'Decoration Ideas' }}</span>
                            <i class="fa-solid fa-circle" aria-hidden="true"></i>
                            <span>{{ $featuredPost->reading_minutes ?: 4 }} Min Read</span>
                        </div>

                        <h3>
                            <a href="{{ $featuredPost->url() }}">{{ $featuredPost->title }}</a>
                        </h3>

                        <p>{{ $featuredPost->excerpt }}</p>

                        <a href="{{ $featuredPost->url() }}" class="blog-featured-link">
                            Read Featured Article
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @endif
        </div>
    </section>

    <section class="blog-latest-section" id="latestBlogs" aria-labelledby="latestBlogsTitle">
        <div class="container">
            <div class="blog-latest-header">
                <div>
                    <span class="section-eyebrow">Latest Articles</span>
                    <h2 class="section-title" id="latestBlogsTitle">Wedding &amp; Event Ideas</h2>
                    <p class="section-description">{{ $page->contentValue('copy.text_6') ?? '' }}</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($latestPosts as $post)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <article class="blog-card">
                            <a href="{{ $post->url() }}" class="blog-card-image" aria-label="Read {{ $post->title }}">
                                <img src="{{ $post->imageUrl() }}"
                                     alt="{{ $post->image_alt ?: $post->title }}"
                                     loading="lazy"
                                     decoding="async">
                                <span class="blog-card-category">
                                    {{ $post->category?->name ?: 'Decoration Ideas' }}
                                </span>
                            </a>

                            <div class="blog-card-content">
                                <div class="blog-card-meta">
                                    <span>{{ $post->category?->name ?: 'Decoration Ideas' }}</span>
                                    <i class="fa-solid fa-circle" aria-hidden="true"></i>
                                    <span>{{ $post->reading_minutes ?: 4 }} Min Read</span>
                                </div>

                                <h3><a href="{{ $post->url() }}">{{ $post->title }}</a></h3>
                                <p>{{ $post->excerpt }}</p>

                                <a href="{{ $post->url() }}" class="blog-card-link">
                                    Read Article
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <p>{{ site_label('content_blogs_empty') ?? '' }}</p>
                    </div>
                @endforelse
            </div>

            @if ($posts->hasPages())
                <div class="blog-pagination">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <section class="blog-topics-section" aria-labelledby="blogTopicsTitle">
        <div class="container">
            <div class="section-heading section-heading-center">
                <span class="section-eyebrow">Explore Topics</span>
                <h2 class="section-title" id="blogTopicsTitle">
                    {{ $page->contentValue('copy.text_19') ?? 'Decoration Ideas by Celebration' }}
                </h2>
                <p class="section-description">{{ $page->contentValue('copy.text_20') ?? '' }}</p>
            </div>

            <div class="row g-3">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="blog-topic-card">
                        <div class="blog-topic-icon"><i class="fa-solid fa-heart" aria-hidden="true"></i></div>
                        <h3>Wedding Decoration</h3>
                        <p>{{ $page->contentValue('copy.text_21') ?? '' }}</p>
                        <a href="{{ url('/services/wedding-decoration') }}">
                            Explore Decoration
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="blog-topic-card">
                        <div class="blog-topic-icon"><i class="fa-solid fa-sun" aria-hidden="true"></i></div>
                        <h3>Haldi &amp; Mehendi</h3>
                        <p>{{ $page->contentValue('copy.text_22') ?? '' }}</p>
                        <a href="{{ url('/services/haldi-decoration') }}">
                            View Functions
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="blog-topic-card">
                        <div class="blog-topic-icon"><i class="fa-solid fa-gift" aria-hidden="true"></i></div>
                        <h3>Party Decoration</h3>
                        <p>{{ $page->contentValue('copy.text_23') ?? '' }}</p>
                        <a href="{{ url('/services/balloon-decoration') }}">
                            Explore Ideas
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="blog-topic-card">
                        <div class="blog-topic-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div>
                        <h3>Planning Tips</h3>
                        <p>{{ $page->contentValue('copy.text_24') ?? '' }}</p>
                        <a href="{{ url('/contact-us') }}">
                            Plan Your Event
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-final-section" aria-labelledby="blogFinalTitle">
        <div class="container">
            <div class="blog-final-card">
                <div class="blog-final-content">
                    <span class="section-eyebrow">{{ $page->contentValue('copy.text_25') ?? 'Ready When You Are' }}</span>
                    <h2 id="blogFinalTitle">{{ $page->contentValue('copy.text_26') ?? '' }}</h2>
                    <p>{{ $page->contentValue('copy.text_27') ?? '' }}</p>

                    <div class="blog-final-actions">
                        <a href="{{ url('/contact-us') }}">
                            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                            Send Enquiry
                        </a>

                        @if (filled($profile->whatsappUrl()))
                            <a href="{{ $profile->whatsappUrl('Hello! I was reading your decoration blog and would like to plan my event.') }}"
                               target="_blank"
                               rel="noopener">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                WhatsApp Us
                            </a>
                        @endif

                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            {{ $profile->phone() }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
