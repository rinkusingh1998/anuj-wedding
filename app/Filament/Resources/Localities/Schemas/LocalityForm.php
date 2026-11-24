<?php

namespace App\Filament\Resources\Localities\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use App\Models\Locality;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * Locality / area / neighbourhood add-edit form.
 */
class LocalityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Location')
                    ->description('Choose the parent state and city, then the locality name. The URL is generated automatically.')
                    ->schema([
                        Select::make('state_id')
                            ->label('State')
                            ->relationship('state', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('city_id')
                            ->label('City')
                            ->relationship('city', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('name')
                            ->label('Locality / Area Name')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->helperText('The page address: /{state}/{city}/area/{slug}')
                            ->required()
                            ->maxLength(160),

                        Select::make('type')
                            ->label('Area Type')
                            ->options(Locality::TYPES)
                            ->default('locality')
                            ->required(),

                        TextInput::make('pincode')
                            ->label('Pincode')
                            ->maxLength(10),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric(),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),
                    ])
                    ->columns(2),

                Section::make('2. Visibility & Order')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Show this locality on the website')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured locality'),

                        Toggle::make('is_service_area')
                            ->label('Part of the active service area')
                            ->default(true),

                        TextInput::make('priority')
                            ->label('Sitemap Priority (1-100)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(50),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Section::make('3. Page Content')
                    ->description('Leave a field empty to render nothing. No placeholder marketing copy is added.')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small Label Above Heading')
                            ->maxLength(120),

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
                            ->helperText('HTML is allowed.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Textarea::make('about_content')
                            ->label('About Content')
                            ->rows(4)
                            ->columnSpanFull(),

                        TagsInput::make('highlights')
                            ->label('Highlights')
                            ->helperText('One point per item. Press Enter to add.')
                            ->columnSpanFull(),

                        Repeater::make('faqs')
                            ->label('FAQs')
                            ->schema([
                                TextInput::make('question')->required()->maxLength(255),
                                Textarea::make('answer')->required()->rows(3),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                            ->addActionLabel('Add FAQ')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('4. Images')
                    ->schema([
                        MediaUpload::make('image')
                            ->label('Main / Hero Image')
                            ->image()
                            ->disk('public')
                            ->directory('localities')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120),

                        MediaUpload::make('og_image')
                            ->label('Social Share Image')
                            ->image()
                            ->disk('public')
                            ->directory('localities/og')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120),

                        Repeater::make('gallery')
                            ->label('Gallery Images')
                            ->schema([
                                MediaUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('localities/gallery')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->required(),
                                TextInput::make('title')->label('Title / Alt Text')->maxLength(255),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->addActionLabel('Add gallery image')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                SeoFields::section(),
                SeoFields::socialSection(),
            ]);
    }
}
