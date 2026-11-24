<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Redirect Rule')
                    ->description('Old URLs keep working after a slug change. Paths are stored without a domain.')
                    ->schema([
                        TextInput::make('from_path')
                            ->label('From Path')
                            ->placeholder('/old-page')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('to_path')
                            ->label('To Path or URL')
                            ->placeholder('/new-page')
                            ->required()
                            ->maxLength(255),

                        Select::make('status_code')
                            ->label('Redirect Type')
                            ->options([
                                301 => '301 - Permanent',
                                302 => '302 - Temporary',
                                307 => '307 - Temporary (keep method)',
                                308 => '308 - Permanent (keep method)',
                            ])
                            ->default(301)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        TextInput::make('hits')
                            ->label('Hit Count')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('note')
                            ->label('Internal Note')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
