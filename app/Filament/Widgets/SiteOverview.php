<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Cities\CityResource;
use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Filament\Resources\Localities\LocalityResource;
use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\States\StateResource;
use App\Models\City;
use App\Models\Enquiry;
use App\Models\Locality;
use App\Models\Media;
use App\Models\Page;
use App\Models\Service;
use App\Models\State;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard summary: the numbers the site owner checks first.
 *
 * Every card links to the screen where that number can be changed.
 */
class SiteOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Website at a glance';

    protected ?string $description = 'Click a card to open that section.';

    protected function getStats(): array
    {
        $newEnquiries = Enquiry::query()->where('status', 'new')->count();

        $totalStates = State::query()->count();
        $visibleStates = State::query()->where('is_visible', true)->count();

        $totalCities = City::query()->count();
        $visibleCities = City::query()->where('is_visible', true)->count();

        $totalLocalities = Locality::query()->count();
        $visibleLocalities = Locality::query()->where('is_visible', true)->count();

        return [
            Stat::make('New enquiries', (string) $newEnquiries)
                ->description($newEnquiries === 0 ? 'No unread leads right now' : 'Waiting for a reply')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($newEnquiries === 0 ? 'gray' : 'warning')
                ->url(EnquiryResource::getUrl()),

            Stat::make('States', (string) $totalStates)
                ->description($visibleStates.' visible on website')
                ->descriptionIcon('heroicon-m-map')
                ->color('success')
                ->url(StateResource::getUrl()),

            Stat::make('Cities & villages', (string) $totalCities)
                ->description($visibleCities.' visible on website')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info')
                ->url(CityResource::getUrl()),

            Stat::make('Villages, localities & areas', (string) $totalLocalities)
                ->description($visibleLocalities.' visible on website')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('warning')
                ->url(LocalityResource::getUrl()),

            Stat::make('Pages', (string) Page::query()->where('is_visible', true)->count())
                ->description('Visible and indexable')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->url(PageResource::getUrl()),

            Stat::make('Services', (string) Service::query()->where('is_visible', true)->count())
                ->description('Shown on the website')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info')
                ->url(ServiceResource::getUrl()),

            Stat::make('Media files', (string) Media::query()->count())
                ->description('Images in the library')
                ->descriptionIcon('heroicon-m-photo')
                ->color('gray')
                ->url(MediaResource::getUrl()),
        ];
    }
}
