<?php

namespace App\Filament\Resources\DispatchedTrips;

use App\Filament\Resources\DispatchedTrips\Pages\CreateDispatchedTrips;
use App\Filament\Resources\DispatchedTrips\Pages\EditDispatchedTrips;
use App\Filament\Resources\DispatchedTrips\Pages\ListDispatchedTrips;
use App\Filament\Resources\DispatchedTrips\Pages\ViewDispatchedTrips;
use App\Filament\Resources\DispatchedTrips\Schemas\DispatchedTripsForm;
use App\Filament\Resources\DispatchedTrips\Schemas\DispatchedTripsInfolist;
use App\Filament\Resources\DispatchedTrips\Tables\DispatchedTripsTable;
use App\Models\DispatchedTrips;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DispatchedTripsResource extends Resource
{
    protected static ?string $model = DispatchedTrips::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'Trips';

    // protected static UnitEnum|string|null $navigationGroup = 'DISPATCH TRIPS';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DispatchedTripsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DispatchedTripsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispatchedTripsTable::configure($table);
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
            'index' => ListDispatchedTrips::route('/'),
            'create' => CreateDispatchedTrips::route('/create'),
            'view' => ViewDispatchedTrips::route('/{record}'),
            'edit' => EditDispatchedTrips::route('/{record}/edit'),
        ];
    }
}
