<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Font Awesome -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
>
<link rel="stylesheet" href="{{ asset('assets/css/all-fontawesome.min.css') }}">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Marcellus&family=Playfair+Display:wght@500;600;700&display=swap"
    rel="stylesheet"
>

<!-- Favicon Icon -->
<link rel="icon" type="image/x-icon" href="{{ $profile->favicon() }}">

<!-- Website CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}?v=20261122">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=20261122">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v=20261122">
<link rel="stylesheet" href="{{ asset('assets/css/premium-redesign.css') }}?v=20261126d">


{{-- =========================================================
     LOCAL BUSINESS / WEBSITE SCHEMA
     (Admin panel > SEO & Sitemap se control hota hai)
========================================================= --}}

@isset($siteSchema)
<script type="application/ld+json">
{!! $siteSchema !!}
</script>
@endisset

{{-- Home page ka FAQ schema --}}
@isset($homeFaqSchema)
<script type="application/ld+json">
{!! $homeFaqSchema !!}
</script>
@endisset
