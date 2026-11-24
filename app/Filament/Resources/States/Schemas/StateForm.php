<?php

namespace App\Filament\Resources\States\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * State add/edit form.
 *
 * Zaroori nahi ki saari SEO fields bharein â€” jo khaali chhod denge
 * unke liye landing page par automatic unique content ban jata hai.
 */
class StateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('1. State Basics')
                    ->description('The state name and its URL. The URL is created automatically from the name.')
                    ->schema([
                        TextInput::make('name')
                            ->label('State Name')
                            ->placeholder('Uttar Pradesh')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->placeholder('uttar-pradesh')
                            ->helperText('The page address is built from this slug: yoursite.com/uttar-pradesh')
                            ->required()
                            ->maxLength(140)
                            ->unique(ignoreRecord: true),

                        TextInput::make('region')
                            ->label('Region (optional)')
                            ->placeholder('Delhi NCR / Purvanchal')
                            ->maxLength(120),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->helperText('A smaller number appears first.')
                            ->numeric()
                            ->default(0),

                        TextInput::make('priority')
                            ->label('Sitemap Priority (1-100)')
                            ->helperText('100 = the most important page.')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(50),
                    ])
                    ->columns(2),

                Section::make('2. Show / Hide This State')
                    ->description('Turning this OFF makes every page of this state return 404 and removes it from the sitemap.')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Show this state on website')
                            ->helperText('ON = indexed by Google and included in the sitemap. OFF = page returns 404.')
                            ->default(true)
                            ->inline(false),

                        Toggle::make('is_default')
                            ->label('Main service area')
                            ->helperText('Tick only one main state (Uttar Pradesh).')
                            ->default(false)
                            ->inline(false),
                    ])
                    ->columns(2),

                SeoFields::section('3. Google Search (SEO)'),
                SeoFields::socialSection(),

                Section::make('4. Images')
                    ->description('Upload images from your computer. Empty = the website uses a default image. You can replace or delete any image here.')
                    ->schema([
                        MediaUpload::make('image')
                            ->label('Main / Hero Image')
                            ->helperText('Used at the top of this state page and in social previews.')
                            ->image()
                            ->disk('public')
                            ->directory('states')
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
                            ->directory('states/og')
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
                                    ->directory('states/gallery')
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

                Section::make('5. Page Content')
                    ->description('Write your own content here. Empty fields render nothing - no placeholder marketing copy is added.')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small Label Above Heading')
                            ->maxLength(120)
                            ->columnSpanFull(),

                        TextInput::make('heading')
                            ->label('Page Heading (H1)')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('sub_heading')
                            ->label('Sub Heading')
                            ->maxLength(255)
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

                Section::make('6. FAQs')
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
