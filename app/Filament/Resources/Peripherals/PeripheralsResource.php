<?php

namespace App\Filament\Resources\Peripherals;

use App\Filament\Resources\Peripherals\Pages\CreatePeripherals;
use App\Filament\Resources\Peripherals\Pages\EditPeripherals;
use App\Filament\Resources\Peripherals\Pages\ListPeripherals;
use App\Filament\Resources\Peripherals\Pages\ViewPeripherals;
use App\Filament\Resources\Peripherals\Schemas\PeripheralsForm;
use App\Filament\Resources\Peripherals\Schemas\PeripheralsInfolist;
use App\Filament\Resources\Peripherals\Tables\PeripheralsTable;
use App\Models\Peripherals;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PeripheralsResource extends Resource
{
    protected static ?string $model = Peripherals::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $recordTitleAttribute = 'Peripherals';

    protected static UnitEnum|string|null $navigationGroup = 'Computer Inventory';

    public static function form(Schema $schema): Schema
    {
        return PeripheralsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PeripheralsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PeripheralsTable::configure($table);
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
            'index' => ListPeripherals::route('/'),
            'create' => CreatePeripherals::route('/create'),
            'view' => ViewPeripherals::route('/{record}'),
            'edit' => EditPeripherals::route('/{record}/edit'),
        ];
    }
}
