<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(180)
                            ->unique(ignoreRecord: true),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin (full access)',
                                'editor' => 'Editor (content only)',
                            ])
                            ->default('editor')
                            ->required()
                            ->helperText('Editors cannot open users, global settings, redirects, business profile or sitemap settings.'),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Leave empty to keep the current password.'),
                    ])
                    ->columns(2),
            ]);
    }
}
