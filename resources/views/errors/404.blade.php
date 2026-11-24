@php
    try {
        $siteName = site_setting('site_title') ?: config('app.name');
        $phone = site_setting('phone');
    } catch (\Throwable) {
        $siteName = config('app.name');
        $phone = null;
    }

    $homeUrl = url('/');
    $servicesUrl = url('/services');
    $areasUrl = url('/locations');
    $contactUrl = url('/contact-us');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, follow">
    <title>Page not found | {{ $siteName }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        body { font-family: 'DM Sans', system-ui, sans-serif; background: #faf6f1; color: #2b2118; margin: 0; }
        .error-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .error-card { max-width: 640px; width: 100%; background: #fff; border-radius: 18px; padding: 48px 36px; text-align: center; box-shadow: 0 18px 60px rgba(43, 33, 24, .08); }
        .error-code { font-size: 72px; line-height: 1; font-weight: 700; color: #b07d3f; margin: 0 0 12px; }
        .error-card h1 { font-size: 26px; margin: 0 0 12px; }
        .error-card p { color: #6b5c4c; margin: 0 0 26px; }
        .error-actions { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; }
        .error-actions a { display: inline-block; padding: 12px 22px; border-radius: 999px; text-decoration: none; font-weight: 600; border: 1px solid #b07d3f; color: #b07d3f; }
        .error-actions a.primary { background: #b07d3f; color: #fff; }
    </style>
</head>
<body>
    <main class="error-wrap">
        <div class="error-card">
            <p class="error-code">404</p>
            <h1>We couldn't find that page</h1>
            <p>The page may have been moved, unpublished, or the address may be mistyped.</p>

            <div class="error-actions">
                <a class="primary" href="{{ $homeUrl }}">Go to home page</a>
                <a href="{{ $servicesUrl }}">Our services</a>
                <a href="{{ $areasUrl }}">Service areas</a>
                @if (filled($phone))
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" data-cta="contact">Call us</a>
                @else
                    <a href="{{ $contactUrl }}">Contact us</a>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
