<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

/**
 * /sitemap.xml aur /robots.txt serve karta hai.
 *
 * Agar public folder me file maujood hai (jo model save hone par
 * apne aap ban jati hai) to wahi serve hoti hai - is liye bahut fast hai.
 * File na mile to yahin se generate kar di jati hai.
 */
class SitemapController extends Controller
{
    public function index(SitemapService $sitemap): Response
    {
        $file = public_path('sitemap.xml');

        $xml = File::exists($file) ? File::get($file) : $sitemap->xml();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function robots(SitemapService $sitemap): Response
    {
        $file = public_path('robots.txt');

        if (! File::exists($file)) {
            $sitemap->writeRobotsTxt();
        }

        return response(File::get($file), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
