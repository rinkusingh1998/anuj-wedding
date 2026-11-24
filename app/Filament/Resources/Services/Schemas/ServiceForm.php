<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * Service add/edit form.
 *
 * A service creates a page for every city:
 * /{state}/{city}/{service}, for example /uttar-pradesh/khurja/wedding-decoration.
 *
 * The sections below follow the same order as the public page so it is clear
 * what each field changes.
 */
class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('1. Service Basics')
                    ->description('The service name and its URL. The URL is created automatically from the name.')
                    ->schema([
                        Select::make('service_category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('No category')
                            ->helperText('Groups this service in the admin panel, for example "Wedding Functions". Create categories under Services → Service Categories.')
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Service Name')
                            ->placeholder('Wedding Decoration')
                            ->required()
                            ->maxLength(140)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->placeholder('wedding-decoration')
                            ->helperText('The page address: yoursite.com/uttar-pradesh/khurja/wedding-decoration')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true),

                        TextInput::make('short_name')
                            ->label('Short Name (for menus and headings)')
                            ->placeholder('Wedding DÃ©cor')
                            ->maxLength(140),

                        TextInput::make('icon')
                            ->label('Icon Class (optional)')
                            ->placeholder('fa-solid fa-heart')
                            ->helperText('Font Awesome class, for example fa-solid fa-cake-candles')
                            ->maxLength(120),

                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('2. Show / Hide This Service')
                    ->description('OFF = every "{service} in {city}" page returns 404 and is removed from the sitemap.')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Show this service on the website')
                            ->helperText('ON = visible and indexed by Google. OFF = hidden everywhere.')
                            ->default(true)
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

                Section::make('3. Page Content')
                    ->description('This text is used on every city page of this service. If left empty, SEO-friendly text is generated automatically.')
                    ->schema([
                        Textarea::make('description')
                            ->label('Short Description')
                            ->helperText('1-2 sentences. Appears in the intro paragraph of every city page.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('long_description')
                            ->label('Long Description')
                            ->helperText('Optional. Used for the longer service description and SEO content.')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                SeoFields::section('4. Google Search (SEO)'),
                SeoFields::socialSection(),

                Section::make('5. Images')
                    ->description('Upload images from your computer. Empty = the website uses a default image. You can replace or delete any image here.')
                    ->schema([
                        MediaUpload::make('image')
                            ->label('Main / Hero Image')
                            ->helperText('Used at the top of the service page and in social previews.')
                            ->image()
                            ->disk('public')
                            ->directory('services')
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
                            ->directory('services/og')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Repeater::make('gallery')
                            ->label('Gallery Images')
                            ->helperText('Add photos of this service. Add, edit, reorder or delete any item.')
                            ->schema([
                                MediaUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('services/gallery')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->required(),

                                TextInput::make('title')
                                    ->label('Title / Alt Text')
                                    ->helperText('Describes the image for Google and screen readers.')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add gallery image')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? $state['image'] ?? null)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('6. Extra Content Sections')
                    ->description('Add any section you want on the service page: heading, text, image and button. These sections appear above the booking CTA. Add, edit, reorder or delete them at any time.')
                    ->schema([
                        Repeater::make('content.sections')
                            ->label('Content Sections')
                            ->schema([
                                TextInput::make('eyebrow')
                                    ->label('Small Label (optional)')
                                    ->maxLength(120),

                                TextInput::make('heading')
                                    ->label('Section Heading')
                                    ->maxLength(255),

                                RichEditor::make('body')
                                    ->label('Section Text')
                                    ->helperText('Use the toolbar for bold, lists, links and headings.')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('services/content')
                                    ->fileAttachmentsVisibility('public')
                                    ->columnSpanFull(),

                                MediaUpload::make('image')
                                    ->label('Section Image (optional)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('services/sections')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120),

                                Select::make('image_position')
                                    ->label('Image Position')
                                    ->options([
                                        'right' => 'Image on the right',
                                        'left' => 'Image on the left',
                                        'none' => 'No image',
                                    ])
                                    ->default('right'),

                                TextInput::make('button_label')
                                    ->label('Button Text (optional)')
                                    ->maxLength(80),

                                TextInput::make('button_url')
                                    ->label('Button Link (optional)')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? $state['eyebrow'] ?? null)
                            ->addActionLabel('Add content section')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
