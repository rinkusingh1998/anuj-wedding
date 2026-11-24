<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website routes
|--------------------------------------------------------------------------
|
|   /                             -> Home (pages table)
|   /about-us, /gallery ...       -> designed pages (pages table)
|   /services/{service}           -> service landing page
|   /packages/{package}           -> package detail page
|   /blogs                        -> blog listing
|   /blogs/category/{category}    -> blog category
|   /blogs/{post}                 -> blog article
|   /locations                    -> service area directory
|   /{state}                      -> state landing page
|   /{state}/{city}               -> city landing page
|   /{state}/{city}/{service}     -> service in city
|   /{state}/{city}/area/{area}   -> locality landing page
|
| Every public page is database driven and only published records resolve.
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/locations', [LocationController::class, 'directory'])->name('locations.index');

Route::get('/blogs', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blogs/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/services/{service}', [ServicePageController::class, 'show'])
    ->where('service', '[A-Za-z0-9\-]+')
    ->name('services.show');

Route::post('/enquiry', [ContactController::class, 'store'])
    ->middleware('throttle:12,1')
    ->name('enquiry.store');

// Catch-all. Registered last so it never shadows the routes above.
Route::fallback([LocationController::class, 'fallback'])->name('index');
