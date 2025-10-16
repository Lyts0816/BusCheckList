<?php

namespace App\Filament\Resources\SystemUnits;

use App\Filament\Resources\SystemUnits\Pages\CreateSystemUnit;
use App\Filament\Resources\SystemUnits\Pages\EditSystemUnit;
use App\Filament\Resources\SystemUnits\Pages\ListSystemUnits;
use App\Filament\Resources\SystemUnits\Pages\ViewSystemUnit;
use App\Filament\Resources\SystemUnits\Schemas\SystemUnitForm;
use App\Filament\Resources\SystemUnits\Schemas\SystemUnitInfolist;
use App\Filament\Resources\SystemUnits\Tables\SystemUnitsTable;
use App\Models\SystemUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SystemUnitResource extends Resource
{
    protected static ?string $model = SystemUnit::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $recordTitleAttribute = 'System Unit';

    protected static UnitEnum|string|null $navigationGroup = 'COMPUTER & PERIPHERALS';

    public static function form(Schema $schema): Schema
    {
        return SystemUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SystemUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SystemUnitsTable::configure($table);
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
            'index' => ListSystemUnits::route('/'),
            'create' => CreateSystemUnit::route('/create'),
            'view' => ViewSystemUnit::route('/{record}'),
            'edit' => EditSystemUnit::route('/{record}/edit'),
        ];
    }
}
