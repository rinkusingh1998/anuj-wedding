<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Locality;
use App\Models\Page as PageModel;
use App\Models\SeoSetting;
use App\Models\Service;
use App\Models\State;
use App\Providers\AppServiceProvider;
use App\Services\SitemapService;
use App\Support\CanonicalUrl;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

/**
 * "SEO & Sitemap" admin page.
 *
 * Yahan se:
 *  - Site ka title, meta description, WhatsApp number, Google Analytics etc. set hote hain.
 *  - Sitemap ek click me dobara ban jata hai.
 *  - Uttar Pradesh ke shehar + services import ho jate hain.
 *  - Saara cache saaf ho jata hai.
 *
 * @property-read Schema $form
 */
class SeoSitemapSettings extends Page
{
    /**
     * Form ka data (har key SeoSetting table me jaati hai).
     *
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static ?string $title = 'SEO & Sitemap';

    protected static ?string $navigationLabel = 'SEO & Sitemap';

    protected static string|\UnitEnum|null $navigationGroup = 'SEO';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.seo-sitemap';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(SeoSetting::values());
    }

    /*
    |--------------------------------------------------------------------------
    | Page content
    |--------------------------------------------------------------------------
    */

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getStatsSection(),
            $this->getIndexingSection(),
            $this->getFormContentComponent(),
        ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment('start')
                    ->key('form-actions'),
            ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Website Identity')
                ->description('Website name, tagline and home page meta text. This is what people see first in Google.')
                ->schema([
                    TextInput::make('site_title')
                        ->label(SeoSetting::DEFAULTS['site_title']['label'])
                        ->required()
                        ->maxLength(255),

                    TextInput::make('site_tagline')
                        ->label(SeoSetting::DEFAULTS['site_tagline']['label'])
                        ->maxLength(255),

                    TextInput::make('home_meta_title')
                        ->label(SeoSetting::DEFAULTS['home_meta_title']['label'])
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('home_meta_description')
                        ->label(SeoSetting::DEFAULTS['home_meta_description']['label'])
                        ->rows(2)
                        ->maxLength(320)
                        ->columnSpanFull(),

                    Textarea::make('meta_keywords')
                        ->label(SeoSetting::DEFAULTS['meta_keywords']['label'])
                        ->rows(2)
                        ->columnSpanFull(),

                    TextInput::make('og_image')
                        ->label(SeoSetting::DEFAULTS['og_image']['label'])
                        ->helperText('This image appears when the link is shared on WhatsApp or Facebook.')
                        ->url()
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Sitemap')
                ->description('The sitemap is generated automatically. You only need to choose a change frequency here.')
                ->schema([
                    Select::make('sitemap_changefreq')
                        ->label(SeoSetting::DEFAULTS['sitemap_changefreq']['label'])
                        ->options([
                            'always' => 'always',
                            'hourly' => 'hourly',
                            'daily' => 'daily',
                            'weekly' => 'weekly',
                            'monthly' => 'monthly',
                            'yearly' => 'yearly',
                            'never' => 'never',
                        ])
                        ->default('weekly')
                        ->selectablePlaceholder(false),
                ]),

            Section::make('Analytics & Verification')
                ->description('Enter your Google Analytics, Tag Manager and Search Console IDs here.')
                ->schema([
                    TextInput::make('google_analytics_id')
                        ->label(SeoSetting::DEFAULTS['google_analytics_id']['label'])
                        ->placeholder('G-XXXXXXXXXX'),

                    TextInput::make('google_tag_manager_id')
                        ->label(SeoSetting::DEFAULTS['google_tag_manager_id']['label'])
                        ->placeholder('GTM-XXXXXXX'),

                    TextInput::make('google_site_verification')
                        ->label(SeoSetting::DEFAULTS['google_site_verification']['label'])
                        ->helperText('Paste the "HTML tag" code from Search Console (content value only).'),

                    TextInput::make('whatsapp_number')
                        ->label(SeoSetting::DEFAULTS['whatsapp_number']['label'])
                        ->helperText('With country code, for example 917302015048')
                        ->maxLength(20),
                ])
                ->columns(2),

        ]);
    }

    /**
     * @return list<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, SeoSetting::DEFAULTS)) {
                SeoSetting::put($key, is_string($value) ? $value : null);
            }
        }

        Cache::forget('site-business-profile');

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->body('Your SEO settings have been saved.')
            ->send();
    }

    /*
    |--------------------------------------------------------------------------
    | Header buttons
    |--------------------------------------------------------------------------
    */

    /**
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('openSearchConsole')
                ->label('Open Search Console')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url('https://search.google.com/search-console', true),

            Action::make('regenerateSitemap')
                ->label('Regenerate sitemap')
                ->icon(Heroicon::OutlinedArrowPath)
                ->action(function (): void {
                    $count = app(SitemapService::class)->write();

                    Notification::make()
                        ->success()
                        ->title('Sitemap ready')
                        ->body($count.' URLs written to sitemap.xml.')
                        ->send();
                }),

            Action::make('importLocations')
                ->label('Import UP cities, services & pages')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Import default data?')
                ->modalDescription('This will add or update all Uttar Pradesh districts and cities, the default services and the website pages. Existing entries and any content you have written will not be deleted.')
                ->action(function (): void {
                    foreach (['Database\\Seeders\\ServiceSeeder', 'Database\\Seeders\\PageSeeder', 'Database\\Seeders\\UttarPradeshSeeder'] as $seeder) {
                        Artisan::call('db:seed', [
                            '--class' => $seeder,
                            '--force' => true,
                        ]);
                    }

                    app(SitemapService::class)->write();

                    Notification::make()
                        ->success()
                        ->title('Import complete')
                        ->body('Cities, services and pages have been added and the sitemap is updated.')
                        ->send();
                }),

            Action::make('clearCache')
                ->label('Clear cache')
                ->icon(Heroicon::OutlinedTrash)
                ->color('gray')
                ->action(function (): void {
                    SeoSetting::flush();
                    Cache::forget('site-business-profile');
                    Cache::forget('sitemap-xml');
                    Cache::forget(AppServiceProvider::LOCATIONS_CACHE_KEY);

                    Notification::make()
                        ->success()
                        ->title('Cache cleared')
                        ->body('The website will now show the latest settings.')
                        ->send();
                }),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Stats
    |--------------------------------------------------------------------------
    */

    private function getStatsSection(): Section
    {
        $stats = app(SitemapService::class)->stats();

        $visibleCities = $stats['cities'];
        $visibleStates = $stats['states'];
        $visibleServices = $stats['services'];

        $totalStates = State::query()->count();
        $totalCities = City::query()->count();
        $totalLocalities = Locality::query()->count();

        $visibleStatePages = State::query()->where('is_visible', true)->count();
        $visibleCityPages = City::query()->where('is_visible', true)->count();
        $visibleLocalities = Locality::query()->where('is_visible', true)->count();

        $hiddenCities = City::query()->where('is_visible', false)->count();
        $hiddenStates = State::query()->where('is_visible', false)->count();
        $hiddenLocalities = Locality::query()->where('is_visible', false)->count();
        $hiddenServices = Service::query()->where('is_visible', false)->count();
        $hiddenPages = PageModel::query()->where('is_visible', false)->count();

        return Section::make('Overview')
            ->description('Visible numbers are the pages currently published on the website. Total numbers include every record saved in the admin panel.')
            ->schema([
                Text::make('States: '.$visibleStatePages.' visible / '.$totalStates.' total')
                    ->badge()
                    ->color('success'),

                Text::make('Cities / villages: '.$visibleCityPages.' visible / '.$totalCities.' total')
                    ->badge()
                    ->color('success'),

                Text::make('Villages / areas: '.$visibleLocalities.' visible / '.$totalLocalities.' total')
                    ->badge()
                    ->color('success'),

                Text::make('Indexable service areas: '.$visibleCities.' cities in '.$visibleStates.' states')
                    ->badge()
                    ->color('info'),

                Text::make('Services: '.$visibleServices)
                    ->badge()
                    ->color('success'),

                Text::make('Website pages: '.$stats['pages'])
                    ->badge()
                    ->color('success'),

                Text::make('Total sitemap URLs: '.$stats['urls'])
                    ->badge()
                    ->color('info'),

                Text::make('Hidden states: '.$hiddenStates)
                    ->badge()
                    ->color($hiddenStates ? 'danger' : 'gray'),

                Text::make('Hidden cities: '.$hiddenCities)
                    ->badge()
                    ->color($hiddenCities ? 'danger' : 'gray'),

                Text::make('Hidden villages / areas: '.$hiddenLocalities)
                    ->badge()
                    ->color($hiddenLocalities ? 'danger' : 'gray'),

                Text::make('Hidden services: '.$hiddenServices)
                    ->badge()
                    ->color($hiddenServices ? 'danger' : 'gray'),

                Text::make('Hidden pages: '.$hiddenPages)
                    ->badge()
                    ->color($hiddenPages ? 'danger' : 'gray'),
            ])
            ->columns(3);
    }

    /**
     * Indexing overview.
     *
     * Google Search Console ka "Indexed / Not indexed" data API se aane ke
     * liye OAuth ya service-account credentials chahiye. Jab tak wo connect
     * nahi hain, yahan wahi numbers dikhaye jaate hain jo hum control karte
     * hain: kaunse pages sitemap me hain (indexable) aur kaunse jaan-boojh kar
     * noindex hain. Isse pata chalta hai GSC me "Not indexed" list me kya
     * expected hai aur kya asli problem.
     */
    private function getIndexingSection(): Section
    {
        $stats = app(SitemapService::class)->stats();
        $base = CanonicalUrl::base() ?? rtrim((string) config('app.url'), '/');

        $indexableCities = City::query()
            ->where('is_visible', true)
            ->where('is_service_area', true)
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('robots_index')->orWhere('robots_index', true))
            ->count();

        $noindexCities = City::query()
            ->where('is_visible', true)
            ->where(function ($query): void {
                $query->where('is_service_area', false)
                    ->orWhere('robots_index', false)
                    ->orWhere(fn ($inner) => $inner->whereNotNull('published_at')->where('published_at', '>', now()));
            })
            ->count();

        return Section::make('Indexing & Search Console')
            ->description('Sitemap me sirf indexable pages jaate hain. Baaki visible city pages jaan-boojh kar noindex hain, is liye Google Search Console unhe "Not indexed" me dikhata hai - ye error nahi hai.')
            ->schema([
                Text::make('Sitemap: '.$base.'/sitemap.xml')
                    ->badge()
                    ->color('info'),

                Text::make('Robots: '.$base.'/robots.txt')
                    ->badge()
                    ->color('info'),

                Text::make('Indexable city pages: '.$indexableCities)
                    ->badge()
                    ->color('success'),

                Text::make('Noindex city pages: '.$noindexCities)
                    ->badge()
                    ->color('warning'),

                Text::make('Total sitemap URLs: '.$stats['urls'])
                    ->badge()
                    ->color('info'),

                Text::make('Google Search Console → Pages report me "Indexed" aur "Not indexed" dono lists dekh sakte hain.')
                    ->color('gray')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
