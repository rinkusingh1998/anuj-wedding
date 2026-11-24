<?php

namespace App\Filament\Resources\Cities\Tables;

use App\Models\City;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * Cities list — search, district filter aur ek click me page on/off.
 */
class CitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('City / Village')
                    ->weight(FontWeight::Bold)
                    ->description(fn (City $record): string => $record->slug)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('state.name')
                    ->label('State')
                    ->badge()
                    ->sortable(),

                TextColumn::make('district')
                    ->label('District')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => City::TYPES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'city' => 'primary',
                        'town' => 'info',
                        'tehsil' => 'warning',
                        'village' => 'success',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_visible')
                    ->label('Show on website')
                    ->onColor('success')
                    ->offColor('danger'),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->onColor('warning')
                    ->offColor('gray')
                    ->toggleable(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label('State')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('district')
                    ->label('District')
                    ->options(fn (): array => City::query()
                        ->whereNotNull('district')
                        ->distinct()
                        ->orderBy('district')
                        ->pluck('district', 'district')
                        ->all())
                    ->searchable(),

                SelectFilter::make('type')
                    ->label('Area Type')
                    ->options(City::TYPES),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All areas')
                    ->trueLabel('Only visible (shown)')
                    ->falseLabel('Only hidden'),

                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->trueLabel('Only featured')
                    ->falseLabel('Only normal'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View page')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (City $record): string => $record->url())
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

                    BulkAction::make('feature')
                        ->label('Mark featured')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true])),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
