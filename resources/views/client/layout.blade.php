<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Title, description, canonical, OG/Twitter tags --}}
    <x-seo-meta :seo="$seo ?? []" :site="$siteSeo ?? []" />

    {{-- links --}}

    <x-head-links />
    @stack('head_links')
</head>
@php
    /**
     * Main image of the current page/entity. CSS backgrounds use this variable,
     * so every hero and CTA image comes from the database (or its fallback)
     * instead of a hard-coded file.
     */
    $heroSource = $page ?? $service ?? $city ?? $state ?? null;

    $pageHeroImage = is_object($heroSource) && method_exists($heroSource, 'imageUrl')
        ? $heroSource->imageUrl()
        : (isset($profile) ? $profile->ogImage() : \App\Support\MediaLibrary::DEFAULT_IMAGE);

    $replaceTemplate = isset($page) && (bool) $page->contentValue('replace_template', false);
@endphp
<body style="--page-image:url('{{ $pageHeroImage }}');">

    <x-header-component />
    <x-sidebar-component />

    @if (session('enquiry_success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('enquiry_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (! $replaceTemplate)
        @yield('content')
    @endif

    @isset($page)
        <x-content-sections :sections="$page->contentValue('sections', [])" :label="$page->name.' content'" />

        @php($pageBlocks = $page->blocks()->visible()->ordered()->get())

        @if ($pageBlocks->isNotEmpty())
            <x-page-blocks :blocks="$pageBlocks" />
        @endif
    @endisset

    <x-floating-icons/>
     <x-footer-component />
     <x-popup />



    {{-- body scripts --}}
    <x-body-scripts />
    @stack('body_scripts')
</body>
</html>
