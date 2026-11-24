<?php

namespace App\Filament\Resources\Packages\Tables;

use App\Models\Package;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->height(40),

                TextColumn::make('name')
                    ->label('Package')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Package $record): string => '/packages/'.$record->slug)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price_from')
                    ->label('Starting Price')
                    ->placeholder('—'),

                TextColumn::make('badge')
                    ->label('Badge')
                    ->badge()
                    ->placeholder('—'),

                ToggleColumn::make('is_visible')
                    ->label('Live')
                    ->onColor('success')
                    ->offColor('danger'),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->onColor('warning')
                    ->offColor('gray')
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All packages')
                    ->trueLabel('Only published')
                    ->falseLabel('Only hidden'),

                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->trueLabel('Only featured')
                    ->falseLabel('Only normal'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View package')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Package $record): string => $record->url())
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
