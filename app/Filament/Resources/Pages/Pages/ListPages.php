<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Services\SitemapService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importDefaults')
                ->label('Import default pages')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Import default website pages?')
                ->modalDescription('This will add all the website pages (Home, About Us, Contact Us, legal pages and so on). Any content you have written stays safe.')
                ->action(function (): void {
                    Artisan::call('db:seed', [
                        '--class' => 'Database\\Seeders\\PageSeeder',
                        '--force' => true,
                    ]);

                    app(SitemapService::class)->write();

                    Notification::make()
                        ->success()
                        ->title('Pages ready')
                        ->body('The default pages have been added and the sitemap is updated.')
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
