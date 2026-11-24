<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('question')
                    ->label('Question')
                    ->weight(FontWeight::Bold)
                    ->limit(80)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('scope')
                    ->label('Scope')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

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
                SelectFilter::make('scope')
                    ->label('Scope')
                    ->options([
                        'global' => 'Global',
                        'home' => 'Home',
                        'contact' => 'Contact',
                        'service' => 'Service',
                    ]),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All FAQs')
                    ->trueLabel('Only visible')
                    ->falseLabel('Only hidden'),

                TrashedFilter::make(),
            ])
            ->recordActions([
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
