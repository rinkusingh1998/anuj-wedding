<?php

namespace App\Support;

/**
 * One public host for every canonical URL, generated link, sitemap entry and
 * redirect.
 *
 * Resolution order:
 *   1. APP_URL, when it points at a real host (not a raw server IP).
 *   2. The incoming request host, when it is a real domain. This covers a
 *      stale APP_URL that still points at the server IP.
 *   3. The configured public_url fallback. Console commands (sitemap
 *      generation) have no request, so they need a stable domain.
 *
 * A "www." prefix is always removed because the canonical site is the
 * non-www domain. An IP-only setup (local XAMPP, health checks) simply gets
 * no canonical base and keeps the framework default.
 */
final class CanonicalUrl
{
    public static function base(): ?string
    {
        foreach ([self::configuredUrl(), self::requestUrl(), self::publicUrl()] as $candidate) {
            if ($candidate !== null) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Host only, e.g. "weddingeventplanners.in".
     */
    public static function host(): ?string
    {
        $base = self::base();

        return $base === null ? null : (string) parse_url($base, PHP_URL_HOST);
    }

    private static function configuredUrl(): ?string
    {
        return self::normalise((string) config('app.url'));
    }

    private static function requestUrl(): ?string
    {
        // In console there is no real request; request() would report
        // "localhost" and poison sitemap URLs.
        if (app()->runningInConsole()) {
            return null;
        }

        $host = (string) request()->getHost();

        if ($host === '' || self::isIp($host)) {
            return null;
        }

        $scheme = request()->isSecure() ? 'https' : 'http';

        return self::normalise($scheme.'://'.$host);
    }

    private static function publicUrl(): ?string
    {
        // The second argument also covers an older cached config that does not
        // contain this key yet.
        $url = (string) config('app.public_url', 'https://weddingeventplanners.in');

        return self::normalise($url !== '' ? $url : 'https://weddingeventplanners.in');
    }

    private static function normalise(string $url): ?string
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        $host = (string) parse_url($url, PHP_URL_HOST);

        if ($host === '' || self::isIp($host)) {
            return null;
        }

        $scheme = (string) parse_url($url, PHP_URL_SCHEME);
        $scheme = $scheme === 'http' ? 'http' : 'https';

        $host = strtolower((string) preg_replace('/^www\./i', '', $host));

        $port = parse_url($url, PHP_URL_PORT);

        if ($port && ! in_array($port, [80, 443], true)) {
            $host .= ':'.$port;
        }

        return $scheme.'://'.$host;
    }

    private static function isIp(string $host): bool
    {
        return filter_var($host, FILTER_VALIDATE_IP) !== false;
    }
}
