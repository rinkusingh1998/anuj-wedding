<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use Filament\Forms\Components\DateTimePicker;
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

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Article')
                    ->schema([
                        Select::make('post_category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),

                        TextInput::make('title')
                            ->label('Article Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->helperText('Public URL: /blog/{slug}')
                            ->required()
                            ->maxLength(180),

                        TextInput::make('eyebrow')
                            ->label('Small Label Above Title')
                            ->maxLength(120),

                        TextInput::make('author')
                            ->label('Author')
                            ->maxLength(120),

                        TextInput::make('reading_minutes')
                            ->label('Reading Time (minutes)')
                            ->numeric()
                            ->minValue(1),

                        Textarea::make('excerpt')
                            ->label('Short Excerpt')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('body')
                            ->label('Article Body')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts/content')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('2. Visibility')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Publish this article')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured article'),

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
                            ->label('Featured Image')
                            ->image()
                            ->disk('public')
                            ->directory('posts')
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
                                    ->directory('posts/gallery')
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
