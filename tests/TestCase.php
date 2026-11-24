<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /*
        |--------------------------------------------------------------------------
        | Sitemap files ko test ke dauraan asli public folder me na likhein
        |--------------------------------------------------------------------------
        |
        | Sitemap generator public/sitemap.xml aur public/robots.txt likhta hai.
        | Tests me hum public path ko ek temporary folder par point kar dete hain,
        | taaki project ki asli files galti se overwrite na ho jayein.
        |
        */

        $testingPublicPath = storage_path('framework/testing/public');

        File::ensureDirectoryExists($testingPublicPath);
        File::cleanDirectory($testingPublicPath);

        $this->app->usePublicPath($testingPublicPath);
    }
}
