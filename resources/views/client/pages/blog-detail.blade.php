@extends('client.layout')

@php
    $articleBody = (string) $post->body;
    $articleBody = preg_replace('/<h1\b([^>]*)>(.*?)<\/h1>/is', '<h2$1>$2</h2>', $articleBody) ?? $articleBody;
    $whatsappUrl = $profile->whatsappUrl('Hello! I read "'.$post->title.'" and would like to discuss decoration for my event.');
    $shareUrl = rawurlencode($post->url());
    $shareText = rawurlencode($post->title);
@endphp

@section('content')
<main class="blog-detail-page">
    <section class="contact-hero-section blog-detail-hero-section" aria-labelledby="blogDetailHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <a href="{{ url('/blogs') }}">Blog</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">{{ $post->title }}</span>
                </nav>

                <div class="blog-detail-meta">
                    @if ($post->category)
                        <span><i class="fa-solid fa-heart" aria-hidden="true"></i> {{ $post->category->name }}</span>
                    @endif

                    @if ($post->reading_minutes)
                        <span><i class="fa-regular fa-clock" aria-hidden="true"></i> {{ $post->reading_minutes }} Min Read</span>
                    @endif

                    @if ($post->published_at)
                        <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> {{ $post->published_at->format('F j, Y') }}</span>
                    @endif
                </div>

                <h1 id="blogDetailHeroTitle">{!! $post->title !!}</h1>

                @if (filled($post->excerpt))
                    <p>{{ $post->excerpt }}</p>
                @endif

                <div class="contact-hero-actions">
                    <a href="#blogArticle" class="contact-primary-btn">
                        Read Article
                        <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                    </a>

                    @if (filled($whatsappUrl))
                        <a href="{{ $whatsappUrl }}" class="contact-outline-btn" target="_blank" rel="noopener">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            WhatsApp Enquiry
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="blog-detail-featured-section">
        <div class="container">
            <div class="blog-detail-featured-wrap">
                <div class="blog-detail-featured-image">
                    <img src="{{ $post->imageUrl() }}"
                         alt="{{ $post->image_alt ?: $post->title }}"
                         loading="eager"
                         decoding="async">
                </div>
            </div>
        </div>
    </section>

    <section class="blog-detail-article-section" id="blogArticle">
        <div class="container">
            <div class="blog-detail-layout">
                <article class="blog-detail-article">
                    <div class="blog-detail-intro">
                        {!! $articleBody !!}
                    </div>

                    @if ($post->galleryItems())
                        <div class="blog-detail-image-grid">
                            @foreach ($post->galleryItems(6) as $image)
                                <img src="{{ $image['image'] }}"
                                     alt="{{ $image['title'] ?: $post->title }}"
                                     loading="lazy"
                                     decoding="async">
                            @endforeach
                        </div>
                    @endif

                    <div class="blog-detail-share">
                        <h2>Share This Article</h2>
                        <div>
                            <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener">
                                <i class="fa-brands fa-facebook-f" aria-hidden="true"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener">
                                <i class="fa-brands fa-x-twitter" aria-hidden="true"></i> Share
                            </a>
                        </div>
                    </div>

                    <div class="blog-detail-article-cta">
                        <div class="blog-detail-article-cta-content">
                            <h2>{{ site_label('content_blog_cta_title') ?? 'Planning a Celebration?' }}</h2>
                            <p>{{ site_label('content_blog_cta_text') ?? '' }}</p>
                        </div>

                        <div class="blog-detail-cta-actions">
                            <a href="{{ url('/contact-us') }}" class="blog-detail-cta-btn">
                                Get a Quote
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>

                            @if (filled($whatsappUrl))
                                <a href="{{ $whatsappUrl }}" class="blog-detail-cta-whatsapp" target="_blank" rel="noopener">
                                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </article>

                <aside class="blog-detail-sidebar">
                    @if ($relatedPosts->isNotEmpty())
                        <div class="blog-detail-sidebar-card">
                            <h2>Related Articles</h2>

                            @foreach ($relatedPosts as $related)
                                <a href="{{ $related->url() }}" class="blog-detail-sidebar-post">
                                    <img src="{{ $related->imageUrl() }}"
                                         alt="{{ $related->image_alt ?: $related->title }}"
                                         loading="lazy"
                                         decoding="async">
                                    <span>{{ $related->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="blog-detail-sidebar-cta">
                        <h2>Need Decoration Help?</h2>
                        <p>Share your date and venue for availability, design ideas and a clear quotation.</p>
                        <a href="{{ url('/contact-us') }}">Send Enquiry</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($relatedPosts->isNotEmpty())
        <section class="blog-detail-related-section" aria-labelledby="relatedArticlesTitle">
            <div class="container">
                <div class="blog-section-heading">
                    <span class="section-eyebrow">Keep Reading</span>
                    <h2 id="relatedArticlesTitle">More Decoration Articles</h2>
                </div>

                <div class="blog-detail-related-grid">
                    @foreach ($relatedPosts as $related)
                        <article class="blog-detail-related-card">
                            <a href="{{ $related->url() }}" class="blog-detail-related-image">
                                <img src="{{ $related->imageUrl() }}"
                                     alt="{{ $related->image_alt ?: $related->title }}"
                                     loading="lazy"
                                     decoding="async">
                            </a>

                            <div class="blog-detail-related-content">
                                <h3><a href="{{ $related->url() }}">{{ $related->title }}</a></h3>
                                <p>{{ $related->excerpt }}</p>
                                <a href="{{ $related->url() }}">
                                    Read More
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</main>
@endsection
