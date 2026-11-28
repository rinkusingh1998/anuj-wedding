{{--
    Single source of truth for <head> metadata.

    Rendered once by client.layout, so a response can never contain duplicate
    title / description / canonical / Open Graph tags.

    $seo  : the page's own SEO payload (App\Support\SeoManager)
    $site : global website settings
--}}
@props([
    'seo' => [],
    'site' => [],
])

@php
    $isHome = request()->is('/');

    $title = filled($seo['title'] ?? null)
        ? $seo['title']
        : ($isHome
            ? (filled($site['home_title'] ?? null) ? $site['home_title'] : ($site['site_title'] ?? config('app.name')))
            : ($site['site_title'] ?? config('app.name')));

    $description = (string) ($seo['description'] ?? '');

    // Google shows about 155-160 characters. Long admin copy is cut at a word
    // boundary so the snippet never ends mid-word.
    $metaDescription = $description;

    if (mb_strlen($metaDescription) > 158) {
        $cut = mb_substr($metaDescription, 0, 158);
        $space = mb_strrpos($cut, ' ');

        if ($space !== false && $space > 110) {
            $cut = mb_substr($cut, 0, $space);
        }

        $metaDescription = rtrim($cut, " \t\n\r\0\x0B,.;:-");
    }
    $canonical = $seo['canonical'] ?? url()->current();
    $robots = $seo['robots'] ?? 'index, follow';
    $keywords = (string) ($seo['keywords'] ?? '');
    $type = $seo['type'] ?? 'website';
    $image = \App\Support\MediaLibrary::url($seo['image'] ?? ($site['image'] ?? null));
    $imageAlt = (string) ($seo['image_alt'] ?? '');

    $og = $seo['og'] ?? [];
    $twitter = $seo['twitter'] ?? [];
    $schemas = $seo['schema'] ?? [];
@endphp

<title>{{ $title }}</title>

@if ($metaDescription !== '')
    <meta name="description" content="{{ $metaDescription }}">
@endif

@if ($keywords !== '')
    <meta name="keywords" content="{{ $keywords }}">
@endif

<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

@if (filled($site['site_verification'] ?? null))
    <meta name="google-site-verification" content="{{ $site['site_verification'] }}">
@endif

{{-- Open Graph (WhatsApp, Facebook, LinkedIn) --}}
<meta property="og:type" content="{{ $og['type'] ?? $type }}">
<meta property="og:title" content="{{ $og['title'] ?? $title }}">
@if (($og['description'] ?? $description) !== '')
    <meta property="og:description" content="{{ $og['description'] ?? $description }}">
@endif
<meta property="og:url" content="{{ $og['url'] ?? $canonical }}">
@if (filled($og['image'] ?? $image))
    @php($ogImage = \App\Support\MediaLibrary::url($og['image'] ?? $image))
    <meta property="og:image" content="{{ $ogImage }}">
    @if (filled($seo['og_image_width'] ?? null) && filled($seo['og_image_height'] ?? null))
        <meta property="og:image:width" content="{{ $seo['og_image_width'] }}">
        <meta property="og:image:height" content="{{ $seo['og_image_height'] }}">
    @endif
    @if ($imageAlt !== '')
        <meta property="og:image:alt" content="{{ $imageAlt }}">
    @endif
@endif
<meta property="og:site_name" content="{{ $og['site_name'] ?? ($site['site_title'] ?? config('app.name')) }}">
<meta property="og:locale" content="en_IN">

{{-- Twitter / X --}}
<meta name="twitter:card" content="{{ $twitter['card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $twitter['title'] ?? $title }}">
@if (($twitter['description'] ?? $description) !== '')
    <meta name="twitter:description" content="{{ $twitter['description'] ?? $description }}">
@endif
@if (filled($twitter['image'] ?? $image))
    <meta name="twitter:image" content="{{ \App\Support\MediaLibrary::url($twitter['image'] ?? $image) }}">
@endif

{{-- Page structured data --}}
@foreach ($schemas as $schema)
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endforeach
