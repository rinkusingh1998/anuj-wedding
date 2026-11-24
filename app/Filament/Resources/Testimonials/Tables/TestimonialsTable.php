<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Models\Testimonial;
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

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->height(40),

                TextColumn::make('name')
                    ->label('Client')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Testimonial $record): ?string => $record->event)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city')
                    ->label('City')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('quote')
                    ->label('Testimonial')
                    ->limit(70)
                    ->wrap(),

                ToggleColumn::make('is_visible')
                    ->label('Show')
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
                    ->placeholder('All testimonials')
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
