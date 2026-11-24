<?php

namespace App\Filament\Resources\States;

use App\Filament\Resources\States\Pages\CreateState;
use App\Filament\Resources\States\Pages\EditState;
use App\Filament\Resources\States\Pages\ListStates;
use App\Filament\Resources\States\Schemas\StateForm;
use App\Filament\Resources\States\Tables\StatesTable;
use App\Models\State;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Admin panel me states (jaise Uttar Pradesh) ki list.
 * Har state ko yahan se show/hide kiya ja sakta hai.
 */
class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationLabel = 'States';

    protected static ?string $modelLabel = 'State';

    protected static ?string $pluralModelLabel = 'States';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Locations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) State::query()->visible()->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Visible states on website';
    }

    public static function form(Schema $schema): Schema
    {
        return StateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStates::route('/'),
            'create' => CreateState::route('/create'),
            'edit' => EditState::route('/{record}/edit'),
        ];
    }
}
