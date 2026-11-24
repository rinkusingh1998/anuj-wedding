<?php

namespace App\Filament\Resources\NavigationItems\Tables;

use App\Models\NavigationItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class NavigationItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')
                    ->label('Menu Item')
                    ->weight(FontWeight::Bold)
                    ->description(fn (NavigationItem $record): ?string => $record->parent?->label)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('menu')
                    ->label('Menu')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => NavigationItem::MENUS[$state] ?? $state),

                TextColumn::make('group')
                    ->label('Group / Column')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('url')
                    ->label('Link')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(),

                ToggleColumn::make('is_visible')
                    ->label('Show')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('menu')
                    ->label('Menu')
                    ->options(NavigationItem::MENUS),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All items')
                    ->trueLabel('Only visible')
                    ->falseLabel('Only hidden'),
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
