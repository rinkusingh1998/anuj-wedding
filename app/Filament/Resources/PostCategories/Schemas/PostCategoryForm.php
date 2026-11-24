<?php

namespace App\Filament\Resources\PostCategories\Schemas;

use App\Filament\Schemas\SeoFields;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Category')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->helperText('Public URL: /blog/category/{slug}')
                            ->required()
                            ->maxLength(180),

                        TextInput::make('eyebrow')
                            ->label('Small Label')
                            ->maxLength(120),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_visible')
                            ->label('Show this category')
                            ->default(true),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->seconds(false),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                SeoFields::section(),
                SeoFields::socialSection(),
            ]);
    }
}
