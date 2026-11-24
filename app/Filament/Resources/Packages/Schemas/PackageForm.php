<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Package')
                    ->schema([
                        TextInput::make('name')
                            ->label('Package Name')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->helperText('Public URL: /packages/{slug}')
                            ->required()
                            ->maxLength(180),

                        TextInput::make('label')
                            ->label('Small Label')
                            ->maxLength(120),

                        TextInput::make('badge')
                            ->label('Badge (e.g. Most Popular)')
                            ->maxLength(60),

                        TextInput::make('price_from')
                            ->label('Starting Price')
                            ->maxLength(60),

                        TextInput::make('price_note')
                            ->label('Price Note')
                            ->maxLength(120),

                        Textarea::make('description')
                            ->label('Short Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        TagsInput::make('features')
                            ->label('Included Features')
                            ->helperText('One feature per item. Press Enter to add.')
                            ->columnSpanFull(),

                        KeyValue::make('content')
                            ->label('Extra Content (optional)')
                            ->helperText('Additional key/value content for this package.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('2. Visibility')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Publish this package')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured package'),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->seconds(false),

                        TextInput::make('priority')
                            ->label('Priority (1-100)')
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

                Section::make('3. Images')
                    ->schema([
                        MediaUpload::make('image')
                            ->label('Main Image')
                            ->image()
                            ->disk('public')
                            ->directory('packages')
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
                                    ->directory('packages/gallery')
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
