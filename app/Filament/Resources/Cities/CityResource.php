<?php

namespace App\Filament\Resources\Cities;

use App\Filament\Resources\Cities\Pages\CreateCity;
use App\Filament\Resources\Cities\Pages\EditCity;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\Cities\Schemas\CityForm;
use App\Filament\Resources\Cities\Tables\CitiesTable;
use App\Models\City;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Admin panel me cities / towns / tehsils / villages ki list.
 *
 * Naya shehar add karte hi us shehar ka page aur uske saare
 * "{service} in {city}" pages + sitemap entry automatic ban jate hain.
 */
class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationLabel = 'Cities & Villages';

    protected static ?string $modelLabel = 'City';

    protected static ?string $pluralModelLabel = 'Cities & Villages';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Locations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) City::query()->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $visible = City::query()->visible()->count();

        return $visible.' visible on website';
    }

    /**
     * State relationship pehle se load, taaki har row ka URL bina extra query ke bane.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('state');
    }

    public static function form(Schema $schema): Schema
    {
        return CityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitiesTable::configure($table);
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
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'edit' => EditCity::route('/{record}/edit'),
        ];
    }
}
