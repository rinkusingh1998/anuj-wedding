<?php

namespace App\Filament\Resources\Redirects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RedirectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('from_path')
                    ->label('Old URL')
                    ->weight(FontWeight::Bold)
                    ->searchable(),

                TextColumn::make('to_path')
                    ->label('New URL')
                    ->searchable(),

                TextColumn::make('status_code')
                    ->label('Type')
                    ->badge()
                    ->color(fn (int $state): string => $state === 301 ? 'success' : 'warning'),

                TextColumn::make('hits')
                    ->label('Hits')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_code')
                    ->label('Redirect Type')
                    ->options([
                        301 => '301 - Permanent',
                        302 => '302 - Temporary',
                        307 => '307 - Temporary',
                        308 => '308 - Permanent',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All redirects')
                    ->trueLabel('Only active')
                    ->falseLabel('Only inactive'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
