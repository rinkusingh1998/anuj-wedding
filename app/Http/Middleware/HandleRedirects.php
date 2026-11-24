<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies admin managed redirects before the router runs.
 *
 * Only exact path matches are used (no wildcards), so a redirect can never
 * create a loop through a pattern. Admin / private routes are skipped.
 */
class HandleRedirects
{
    /**
     * Paths that must never be redirected from this table.
     *
     * @var list<string>
     */
    private const PROTECTED_PREFIXES = ['admin', 'livewire', 'api', 'storage'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = Redirect::normalise($request->path());

        if ($this->isProtected($path)) {
            return $next($request);
        }

        $redirect = Redirect::query()->active()->where('from_path', $path)->first();

        if (! $redirect || Redirect::normalise($redirect->to_path) === $path) {
            return $next($request);
        }

        $redirect->increment('hits');

        return redirect()->to($redirect->targetUrl(), $redirect->status_code);
    }

    private function isProtected(string $path): bool
    {
        foreach (self::PROTECTED_PREFIXES as $prefix) {
            if ($path === '/'.$prefix || str_starts_with($path, '/'.$prefix.'/')) {
                return true;
            }
        }

        return false;
    }
}
