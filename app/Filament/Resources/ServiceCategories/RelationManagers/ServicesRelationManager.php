<?php

namespace App\Filament\Resources\ServiceCategories\RelationManagers;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/**
 * "Services in this category" tab on a category edit page.
 *
 * Services are created and edited in the Services screen; here you only decide
 * which category a service belongs to.
 */
class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    protected static ?string $title = 'Services in this category';

    protected static ?string $modelLabel = 'service';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Service')
                    ->weight(FontWeight::Bold)
                    ->description(fn ($record): string => $record->slug)
                    ->searchable(),

                TextColumn::make('short_name')
                    ->label('Short Name')
                    ->placeholder('—')
                    ->toggleable(),

                ToggleColumn::make('is_visible')
                    ->label('Show on website')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->headerActions([
                AssociateAction::make()
                    ->label('Add existing service')
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                Action::make('editService')
                    ->label('Edit service')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record): string => ServiceResource::getUrl('edit', ['record' => $record])),

                DissociateAction::make()
                    ->label('Remove from category'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make()
                        ->label('Remove from category'),
                ]),
            ]);
    }
}
