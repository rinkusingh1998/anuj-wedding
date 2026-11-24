<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
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
 * Pages list — ek click me page index/noindex.
 */
class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Page')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Page $record): string => '/'.ltrim($record->path(), '/'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('meta_title')
                    ->label('Meta Title')
                    ->limit(60)
                    ->placeholder('— auto —')
                    ->toggleable(),

                TextColumn::make('template')
                    ->label('Layout')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'default'))
                    ->toggleable(),

                ToggleColumn::make('is_visible')
                    ->label('Index in Google')
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
                SelectFilter::make('template')
                    ->label('Layout')
                    ->options(fn (): array => Page::query()
                        ->whereNotNull('template')
                        ->distinct()
                        ->orderBy('template')
                        ->pluck('template', 'template')
                        ->all()),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All pages')
                    ->trueLabel('Only indexed pages')
                    ->falseLabel('Only hidden (noindex) pages'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View page')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Page $record): string => $record->url())
                    ->openUrlInNewTab(),

                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Page $record): string => $record->url().'?preview=1')
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('show')
                        ->label('Index pages')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->update(['is_visible' => true])),

                    BulkAction::make('hide')
                        ->label('Hide from Google')
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
