<?php

namespace App\Filament\Resources\Drivers;

use App\Filament\Resources\Drivers\Pages\CreateDrivers;
use App\Filament\Resources\Drivers\Pages\EditDrivers;
use App\Filament\Resources\Drivers\Pages\ListDrivers;
use App\Filament\Resources\Drivers\Pages\ViewDrivers;
use App\Filament\Resources\Drivers\Schemas\DriversForm;
use App\Filament\Resources\Drivers\Schemas\DriversInfolist;
use App\Filament\Resources\Drivers\Tables\DriversTable;
use App\Models\Drivers;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DriversResource extends Resource
{
    protected static ?string $model = Drivers::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-right';

    protected static ?string $recordTitleAttribute = 'Bus Daily Checklist';

        protected static UnitEnum|string|null $navigationGroup = 'DISPATCH TRIPS';

    public static function form(Schema $schema): Schema
    {
        return DriversForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DriversInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DriversTable::configure($table);
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
            'index' => ListDrivers::route('/'),
            // 'create' => CreateDrivers::route('/create'),
            // 'view' => ViewDrivers::route('/{record}'),
            // 'edit' => EditDrivers::route('/{record}/edit'),
        ];
    }
}
