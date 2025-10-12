<?php

namespace App\Filament\Resources\Buses;

use App\Filament\Resources\Buses\Pages\CreateBus;
use App\Filament\Resources\Buses\Pages\EditBus;
use App\Filament\Resources\Buses\Pages\ListBuses;
use App\Filament\Resources\Buses\Pages\ViewBus;
use App\Filament\Resources\Buses\Schemas\BusForm;
use App\Filament\Resources\Buses\Schemas\BusInfolist;
use App\Filament\Resources\Buses\Tables\BusesTable;
use App\Models\Bus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BusResource extends Resource
{
    protected static ?string $model = Bus::class;

    protected static string|BackedEnum|null $navigationIcon = 'ionicon-bus-outline';

    protected static ?string $recordTitleAttribute = 'Buses';

        protected static UnitEnum|string|null $navigationGroup = 'Bus Checklists';

    public static function form(Schema $schema): Schema
    {
        return BusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusesTable::configure($table);
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
            'index' => ListBuses::route('/'),
            'create' => CreateBus::route('/create'),
            'view' => ViewBus::route('/{record}'),
            'edit' => EditBus::route('/{record}/edit'),
        ];
    }
}
