<?php

namespace App\Filament\Resources\BusNumbers;

use App\Filament\Resources\BusNumbers\Pages\CreateBusNumber;
use App\Filament\Resources\BusNumbers\Pages\EditBusNumber;
use App\Filament\Resources\BusNumbers\Pages\ListBusNumbers;
use App\Filament\Resources\BusNumbers\Pages\ViewBusNumber;
use App\Filament\Resources\BusNumbers\Schemas\BusNumberForm;
use App\Filament\Resources\BusNumbers\Schemas\BusNumberInfolist;
use App\Filament\Resources\BusNumbers\Tables\BusNumbersTable;
use App\Models\BusNumber;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BusNumberResource extends Resource
{
    protected static ?string $model = BusNumber::class;

    protected static string|BackedEnum|null $navigationIcon = 'ionicon-bus-outline';

    protected static ?string $recordTitleAttribute = 'Bus Numbers';

    protected static UnitEnum|string|null $navigationGroup = 'BUS MANAGEMENT';

    public static function form(Schema $schema): Schema
    {
        return BusNumberForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusNumberInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusNumbersTable::configure($table);
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
            'index' => ListBusNumbers::route('/'),
            'create' => CreateBusNumber::route('/create'),
            'view' => ViewBusNumber::route('/{record}'),
            'edit' => EditBusNumber::route('/{record}/edit'),
        ];
    }
}
