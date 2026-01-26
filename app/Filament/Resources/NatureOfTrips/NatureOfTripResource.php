<?php

namespace App\Filament\Resources\NatureOfTrips;

use App\Filament\Resources\NatureOfTrips\Pages\CreateNatureOfTrip;
use App\Filament\Resources\NatureOfTrips\Pages\EditNatureOfTrip;
use App\Filament\Resources\NatureOfTrips\Pages\ListNatureOfTrips;
use App\Filament\Resources\NatureOfTrips\Pages\ViewNatureOfTrip;
use App\Filament\Resources\NatureOfTrips\Schemas\NatureOfTripForm;
use App\Filament\Resources\NatureOfTrips\Schemas\NatureOfTripInfolist;
use App\Filament\Resources\NatureOfTrips\Tables\NatureOfTripsTable;
use App\Models\NatureOfTrip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NatureOfTripResource extends Resource
{
    protected static ?string $model = NatureOfTrip::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-right';

    protected static ?string $recordTitleAttribute = 'Bus Numbers';

    protected static UnitEnum|string|null $navigationGroup = 'DISPATCH TRIPS';

    public static function form(Schema $schema): Schema
    {
        return NatureOfTripForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NatureOfTripInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NatureOfTripsTable::configure($table);
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
            'index' => ListNatureOfTrips::route('/'),
            // 'create' => CreateNatureOfTrip::route('/create'),
            // 'view' => ViewNatureOfTrip::route('/{record}'),
            // 'edit' => EditNatureOfTrip::route('/{record}/edit'),
        ];
    }
}
