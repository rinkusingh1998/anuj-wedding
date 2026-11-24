<?php

namespace App\Filament\Resources\ServiceCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * Service category form. Only the grouping information lives here; the
 * services themselves are managed in the "Services" tab below the form.
 */
class ServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Category Basics')
                    ->description('A short group name, for example "Wedding Functions".')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->placeholder('Wedding Functions')
                            ->required()
                            ->maxLength(140)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('Internal Slug')
                            ->helperText('Used by the admin panel only. Created automatically from the name.')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true),

                        Textarea::make('description')
                            ->label('Short Note (optional)')
                            ->helperText('Explains what belongs in this category. Not shown on the website.')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('icon')
                            ->label('Icon Class (optional)')
                            ->placeholder('fa-solid fa-ring')
                            ->helperText('Font Awesome class, for example fa-solid fa-ring')
                            ->maxLength(120),
                    ])
                    ->columns(2),

                Section::make('2. Order & Visibility')
                    ->description('Controls where the category appears in lists.')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Show this category')
                            ->helperText('OFF = the category is hidden from the admin service grouping.')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->helperText('A smaller number appears first.')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}
