<?php

namespace App\Filament\Resources\NavigationItems\Schemas;

use App\Models\NavigationItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NavigationItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Menu Item')
                    ->schema([
                        Select::make('menu')
                            ->label('Menu')
                            ->options(NavigationItem::MENUS)
                            ->default('header')
                            ->required(),

                        Select::make('parent_id')
                            ->label('Parent Item (optional)')
                            ->relationship('parent', 'label')
                            ->searchable()
                            ->preload(),

                        TextInput::make('label')
                            ->label('Label')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('description')
                            ->label('Description / Subtitle')
                            ->helperText('Shown under the label in the mega menu and mobile menu.')
                            ->maxLength(255),

                        TextInput::make('url')
                            ->label('URL')
                            ->helperText('Example: /about-us. Leave empty when using a named route.')
                            ->maxLength(255),

                        TextInput::make('route')
                            ->label('Named Route (optional)')
                            ->helperText('Example: packages.show. Takes priority over the URL.')
                            ->maxLength(120),

                        TextInput::make('icon')
                            ->label('Font Awesome Icon')
                            ->placeholder('fa-solid fa-house')
                            ->maxLength(120),

                        TextInput::make('badge')
                            ->label('Badge Text')
                            ->maxLength(60),

                        Select::make('target')
                            ->label('Open In')
                            ->options([
                                '_self' => 'Same tab',
                                '_blank' => 'New tab',
                            ])
                            ->default('_self'),

                        TextInput::make('group')
                            ->label('Column / Group')
                            ->helperText('Footer column heading, or mega menu group name.')
                            ->maxLength(120),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_visible')
                            ->label('Show this item')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Mega Menu Presentation')
                    ->description('Optional flags used when this item is a top-level header menu.')
                    ->schema([
                        Toggle::make('meta.is_mega')
                            ->label('Open as full-width mega menu'),

                        Toggle::make('meta.is_cta')
                            ->label('Render as the header call-to-action button'),

                        TextInput::make('meta.group_icon')
                            ->label('Group Icon (for a group heading)')
                            ->placeholder('fa-solid fa-rings-wedding')
                            ->maxLength(120),
                    ])
                    ->columns(3)
                    ->collapsed(),
            ]);
    }
}
