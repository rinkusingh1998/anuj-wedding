<?php

namespace App\Filament\Resources\Media\Tables;

use App\Models\Media;
use App\Support\MediaUsage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->height(48),

                TextColumn::make('title')
                    ->label('Title')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Media $record): ?string => $record->alt_text)
                    ->placeholder('Untitled')
                    ->searchable(),

                TextColumn::make('collection')
                    ->label('Collection')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('mime_type')
                    ->label('Type')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn (?int $state): string => $state ? number_format($state / 1024, 1).' KB' : '—')
                    ->toggleable(),

                TextColumn::make('usage_count')
                    ->label('Used by')
                    ->badge()
                    ->state(fn (Media $record): int => count(app(MediaUsage::class)->usages($record)))
                    ->color(fn ($state): string => ((int) $state) > 0 ? 'warning' : 'success')
                    ->formatStateUsing(fn ($state): string => ((int) $state) > 0 ? ((int) $state).' record(s)' : 'Not used'),

                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('collection')
                    ->label('Collection')
                    ->options([
                        'general' => 'General',
                        'hero' => 'Hero / Banner',
                        'gallery' => 'Gallery',
                        'featured' => 'Featured Image',
                        'logo' => 'Logo',
                        'og' => 'Social Sharing',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('usages')
                    ->label('Where used')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->modalHeading('This file is used by')
                    ->modalDescription(fn (Media $record): string => collect(app(MediaUsage::class)->usages($record))->implode(', ') ?: 'This file is not used by any website content.')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('copyUrl')
                    ->label('Copy URL')
                    ->icon('heroicon-o-link')
                    ->action(fn (Media $record) => null)
                    ->extraAttributes(fn (Media $record): array => [
                        'x-on:click' => 'navigator.clipboard.writeText('.json_encode($record->publicUrl()).')',
                    ]),

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
