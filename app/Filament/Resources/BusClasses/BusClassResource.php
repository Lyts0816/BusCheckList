<?php

namespace App\Filament\Resources\BusClasses;

use App\Filament\Resources\BusClasses\Pages\CreateBusClass;
use App\Filament\Resources\BusClasses\Pages\EditBusClass;
use App\Filament\Resources\BusClasses\Pages\ListBusClasses;
use App\Filament\Resources\BusClasses\Pages\ViewBusClass;
use App\Filament\Resources\BusClasses\Schemas\BusClassForm;
use App\Filament\Resources\BusClasses\Schemas\BusClassInfolist;
use App\Filament\Resources\BusClasses\Tables\BusClassesTable;
use App\Models\BusClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BusClassResource extends Resource
{
    protected static ?string $model = BusClass::class;

    protected static string|BackedEnum|null $navigationIcon = 'ionicon-bus-outline';

    protected static ?string $recordTitleAttribute = 'Bus Classes';

    protected static UnitEnum|string|null $navigationGroup = 'BUS MANAGEMENT';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BusClassForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusClassInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusClassesTable::configure($table);
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
            'index' => ListBusClasses::route('/'),
            // 'create' => CreateBusClass::route('/create'),
            // 'view' => ViewBusClass::route('/{record}'),
            // 'edit' => EditBusClass::route('/{record}/edit'),
        ];
    }
}
