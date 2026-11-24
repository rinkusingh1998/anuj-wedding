<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

/**
 * Simple guide for the site owner.
 *
 * It explains which admin screen changes which part of the website, so content
 * can be updated without guessing.
 */
class WebsiteGuide extends Page
{
    protected static ?string $title = 'Website Content Guide';

    protected static ?string $navigationLabel = 'Website Guide';

    protected static string|\UnitEnum|null $navigationGroup = 'Help';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.website-guide';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
