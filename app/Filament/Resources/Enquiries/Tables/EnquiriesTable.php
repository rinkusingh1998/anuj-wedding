<?php

namespace App\Filament\Resources\Enquiries\Tables;

use App\Models\Enquiry;
use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class EnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Enquiry $record): ?string => $record->email)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('service')
                    ->label('Service')
                    ->placeholder('—')
                    ->limit(30)
                    ->tooltip(fn (Enquiry $record): ?string => $record->service)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event_type')
                    ->label('Event')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('event_date')
                    ->label('Event Date')
                    ->formatStateUsing(fn (?string $state): ?string => filled($state)
                        ? Carbon::parse($state)->format('d M Y')
                        : null)
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('event_time')
                    ->label('Event Time')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('city')
                    ->label('City')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('event_address')
                    ->label('Venue')
                    ->placeholder('—')
                    ->limit(40)
                    ->tooltip(fn (Enquiry $record): ?string => $record->event_address)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('budget')
                    ->label('Budget')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                SelectColumn::make('status')
                    ->label('Status')
                    ->options(Enquiry::STATUSES)
                    ->searchableOptions(false)
                    ->sortable(),

                TextColumn::make('source_page')
                    ->label('Source')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Enquiry::STATUSES),

                SelectFilter::make('service')
                    ->label('Service')
                    ->options(fn (): array => Service::query()
                        ->visible()
                        ->ordered()
                        ->pluck('name', 'name')
                        ->all())
                    ->searchable(),

                SelectFilter::make('city')
                    ->label('City')
                    ->options(fn (): array => Enquiry::query()
                        ->whereNotNull('city')
                        ->where('city', '!=', '')
                        ->distinct()
                        ->orderBy('city')
                        ->pluck('city', 'city')
                        ->all())
                    ->searchable(),

                SelectFilter::make('event_type')
                    ->label('Event Type')
                    ->options(fn (): array => Enquiry::query()
                        ->whereNotNull('event_type')
                        ->where('event_type', '!=', '')
                        ->distinct()
                        ->orderBy('event_type')
                        ->pluck('event_type', 'event_type')
                        ->all()),

                SelectFilter::make('source_page')
                    ->label('Source')
                    ->options(fn (): array => Enquiry::query()
                        ->whereNotNull('source_page')
                        ->where('source_page', '!=', '')
                        ->distinct()
                        ->orderBy('source_page')
                        ->pluck('source_page', 'source_page')
                        ->all()),

                Filter::make('received_between')
                    ->label('Received between')
                    ->schema([
                        DatePicker::make('received_from')->label('From'),
                        DatePicker::make('received_until')->label('Until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['received_from'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['received_until'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '<=', $date),
                        )),

                Filter::make('event_between')
                    ->label('Event date')
                    ->schema([
                        DatePicker::make('event_from')->label('From'),
                        DatePicker::make('event_until')->label('Until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['event_from'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('event_date', '>=', $date),
                        )
                        ->when(
                            $data['event_until'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('event_date', '<=', $date),
                        )),

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
