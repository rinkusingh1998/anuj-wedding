<?php

namespace App\Filament\Resources\States\Tables;

use App\Models\State;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * States list — ek click me page on/off.
 */
class StatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('State')
                    ->weight(FontWeight::Bold)
                    ->description(fn (State $record): string => $record->slug)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('region')
                    ->label('Region')
                    ->badge()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('cities_count')
                    ->label('Cities')
                    ->counts('cities')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('localities_count')
                    ->label('Villages / areas')
                    ->counts('localities')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                ToggleColumn::make('is_visible')
                    ->label('Show on website')
                    ->onColor('success')
                    ->offColor('danger'),

                IconColumn::make('is_default')
                    ->label('Main')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All states')
                    ->trueLabel('Only visible (shown)')
                    ->falseLabel('Only hidden'),

                TernaryFilter::make('is_default')
                    ->label('Main service area')
                    ->trueLabel('Only main state')
                    ->falseLabel('Only other states'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View page')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (State $record): string => $record->url())
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('show')
                        ->label('Show pages')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->update(['is_visible' => true])),

                    BulkAction::make('hide')
                        ->label('Hide pages')
                        ->icon('heroicon-o-eye-slash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->update(['is_visible' => false])),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
