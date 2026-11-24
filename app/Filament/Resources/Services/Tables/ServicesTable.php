<?php

namespace App\Filament\Resources\Services\Tables;

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
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * Services list — ek click me service on/off.
 *
 * Service hide karte hi uske saare city pages aur sitemap URLs hat jate hain.
 */
class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->defaultGroup('category.name')
            ->groups([
                Group::make('category.name')
                    ->label('Category')
                    ->collapsible(),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Service')
                    ->weight(FontWeight::Bold)
                    ->description(fn ($record): string => $record->slug)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->placeholder('No category')
                    ->sortable(),

                TextColumn::make('short_name')
                    ->label('Short Name')
                    ->placeholder('—')
                    ->toggleable(),

                ToggleColumn::make('is_visible')
                    ->label('Show on website')
                    ->onColor('success')
                    ->offColor('danger'),

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
                SelectFilter::make('service_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All services')
                    ->trueLabel('Only visible (shown)')
                    ->falseLabel('Only hidden'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View page')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record): string => $record->url())
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
