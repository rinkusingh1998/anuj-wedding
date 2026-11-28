<?php

namespace App\Http\Middleware;

use App\Support\CanonicalUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps every public URL on the one canonical host.
 *
 * Google treats http://, https://, www., /index.php and trailing-slash
 * variants as separate pages. This middleware sends a single 301 to the
 * canonical URL so only one version is ever indexed.
 *
 * It is deliberately conservative: it does nothing when no real domain can
 * be resolved (local XAMPP, raw-IP requests) and it never touches the
 * health-check endpoint.
 */
class CanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        // Health checks may legitimately hit the raw server IP.
        if ($request->path() === 'up') {
            return $next($request);
        }

        $base = CanonicalUrl::base();

        if ($base === null) {
            return $next($request);
        }

        $baseHost = strtolower((string) parse_url($base, PHP_URL_HOST));
        $currentHost = strtolower($request->getHost());
        $currentPath = $request->getPathInfo() ?: '/';

        // Local development must stay usable even when APP_URL points at the
        // production domain.
        if (in_array($currentHost, ['localhost', '127.0.0.1', '::1'], true)) {
            return $next($request);
        }

        $path = $currentPath;

        // /index.php and /index.php/... are duplicates of the clean URL.
        if ($path === '/index.php') {
            $path = '/';
        } elseif (str_starts_with($path, '/index.php/')) {
            $path = substr($path, strlen('/index.php'));
        }

        // A trailing slash is a different URL for search engines.
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $hostChanged = $baseHost !== '' && $currentHost !== $baseHost;

        if (! $hostChanged && $path === $currentPath) {
            return $next($request);
        }

        $query = $request->getQueryString();

        return redirect()->to(
            $base.$path.($query !== null && $query !== '' ? '?'.$query : ''),
            301,
        );
    }
}
