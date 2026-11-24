<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use App\Models\City;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * City / town / tehsil / village add-edit form.
 *
 * Yahan se naya shehar add karte hi us shehar ka page aur
 * uske saare service pages + sitemap entry automatic ban jate hain.
 */
class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('1. Which Area Is This?')
                    ->description('The state and the name of this place. The URL is created automatically from the name.')
                    ->schema([
                        Select::make('state_id')
                            ->label('State')
                            ->relationship('state', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('name')
                            ->label('City / Town / Village Name')
                            ->placeholder('Khurja')
                            ->required()
                            ->maxLength(140)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->placeholder('khurja')
                            ->helperText('The page address: yoursite.com/uttar-pradesh/khurja')
                            ->required()
                            ->maxLength(160),

                        ToggleButtons::make('type')
                            ->label('Area Type')
                            ->options(City::TYPES)
                            ->default('city')
                            ->inline()
                            ->required(),

                        TextInput::make('district')
                            ->label('District')
                            ->placeholder('Bulandshahr')
                            ->maxLength(140),

                        TextInput::make('pincode')
                            ->label('Pincode')
                            ->placeholder('203131')
                            ->maxLength(10),
                    ])
                    ->columns(2),

                Section::make('2. Map Location')
                    ->description('Adding latitude and longitude makes the Google map schema stronger.')
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric(),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Section::make('3. Show / Hide This City')
                    ->description('Turning this OFF makes every page of this city return 404 and removes it from the sitemap.')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Show this city on website')
                            ->helperText('ON = indexed by Google and included in the sitemap. OFF = page returns 404.')
                            ->default(true)
                            ->inline(false),

                        Toggle::make('is_featured')
                            ->label('Important city (featured)')
                            ->helperText('Featured cities appear at the top of the home page and state page.')
                            ->default(false)
                            ->inline(false),

                        Toggle::make('is_service_area')
                            ->label('Primary active service area')
                            ->helperText('ON = this city appears in service-area lists, drop-downs and the sitemap. OFF = the page stays reachable but is noindex and hidden from default lists.')
                            ->default(false)
                            ->inline(false),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->helperText('A smaller number appears first.')
                            ->numeric()
                            ->default(0),

                        TextInput::make('priority')
                            ->label('Sitemap Priority (1-100)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(50),
                    ])
                    ->columns(2),

                SeoFields::section('4. Google Search (SEO)'),
                SeoFields::socialSection(),

                Section::make('5. Images')
                    ->description('Upload images from your computer. Empty = the website uses a default image. You can replace or delete any image here.')
                    ->schema([
                        MediaUpload::make('image')
                            ->label('Main / Hero Image')
                            ->helperText('Used at the top of this city page and in social previews.')
                            ->image()
                            ->disk('public')
                            ->directory('cities')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        MediaUpload::make('og_image')
                            ->label('Social Share Image (WhatsApp / Facebook)')
                            ->helperText('Optional. Falls back to the main image.')
                            ->image()
                            ->disk('public')
                            ->directory('cities/og')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Repeater::make('gallery')
                            ->label('Gallery Images')
                            ->schema([
                                MediaUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('cities/gallery')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->required(),

                                TextInput::make('title')->label('Title / Alt Text')->maxLength(255),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add gallery image')
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('6. Page Content')
                    ->description('Write your own content here if you want. If left empty, the auto-generated content is used.')
                    ->schema([
                        TextInput::make('heading')
                            ->label('Page Heading (H1)')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('sub_heading')
                            ->label('Sub Heading')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TagsInput::make('highlights')
                            ->label('Highlights (the ticked list on the page)')
                            ->helperText('Type one point and press Enter to add it.')
                            ->columnSpanFull(),

                        Textarea::make('intro_content')
                            ->label('Intro Content')
                            ->helperText('HTML tags are allowed (for example <p>...</p>).')
                            ->rows(4)
                            ->columnSpanFull(),

                        Textarea::make('about_content')
                            ->label('About Content')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('7. FAQs')
                    ->description('These questions create a Google rich result. If left empty, auto-generated FAQs are used.')
                    ->schema([
                        Repeater::make('faqs')
                            ->label('Questions & Answers')
                            ->schema([
                                TextInput::make('question')
                                    ->label('Question')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('answer')
                                    ->label('Answer')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->addActionLabel('Add FAQ')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                            ->defaultItems(0),
                    ])
                    ->collapsed(),

            ]);
    }
}
