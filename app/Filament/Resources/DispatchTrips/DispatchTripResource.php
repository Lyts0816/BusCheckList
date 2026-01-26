<?php

namespace App\Filament\Resources\DispatchTrips;

use App\Filament\Resources\DispatchTrips\Pages\CreateDispatchTrip;
use App\Filament\Resources\DispatchTrips\Pages\EditDispatchTrip;
use App\Filament\Resources\DispatchTrips\Pages\ListDispatchTrips;
use App\Filament\Resources\DispatchTrips\Pages\ViewDispatchTrip;
use App\Filament\Resources\DispatchTrips\Schemas\DispatchTripForm;
use App\Filament\Resources\DispatchTrips\Schemas\DispatchTripInfolist;
use App\Filament\Resources\DispatchTrips\Tables\DispatchTripsTable;
use App\Models\DispatchTrip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DispatchTripResource extends Resource
{
    protected static ?string $model = DispatchTrip::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-right';

    protected static ?string $recordTitleAttribute = 'Bus Daily Checklist';

        protected static UnitEnum|string|null $navigationGroup = 'DISPATCH TRIPS';

    public static function form(Schema $schema): Schema
    {
        return DispatchTripForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DispatchTripInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispatchTripsTable::configure($table);
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
            'index' => ListDispatchTrips::route('/'),
            // 'create' => CreateDispatchTrip::route('/create'),
            // 'view' => ViewDispatchTrip::route('/{record}'),
            // 'edit' => EditDispatchTrip::route('/{record}/edit'),
        ];
    }
}
