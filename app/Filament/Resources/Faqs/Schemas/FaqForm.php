<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Question & Answer')
                    ->schema([
                        TextInput::make('question')
                            ->label('Question')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('answer')
                            ->label('Answer')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('category')
                            ->label('Category')
                            ->maxLength(120),

                        Select::make('scope')
                            ->label('Where should this FAQ appear?')
                            ->options([
                                'global' => 'Global FAQ page',
                                'home' => 'Home page',
                                'contact' => 'Contact page',
                                'service' => 'Service pages',
                            ])
                            ->default('global'),

                        Toggle::make('is_visible')
                            ->label('Show this FAQ')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}
