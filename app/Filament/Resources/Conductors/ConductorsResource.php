<?php

namespace App\Filament\Resources\Conductors;

use App\Filament\Resources\Conductors\Pages\CreateConductors;
use App\Filament\Resources\Conductors\Pages\EditConductors;
use App\Filament\Resources\Conductors\Pages\ListConductors;
use App\Filament\Resources\Conductors\Pages\ViewConductors;
use App\Filament\Resources\Conductors\Schemas\ConductorsForm;
use App\Filament\Resources\Conductors\Schemas\ConductorsInfolist;
use App\Filament\Resources\Conductors\Tables\ConductorsTable;
use App\Models\Conductors;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ConductorsResource extends Resource
{
    protected static ?string $model = Conductors::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'Bus Daily Checklist';

    protected static UnitEnum|string|null $navigationGroup = 'DRIVERS & CONDUCTORS';

    public static function form(Schema $schema): Schema
    {
        return ConductorsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConductorsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConductorsTable::configure($table);
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
            'index' => ListConductors::route('/'),
            // 'create' => CreateConductors::route('/create'),
            // 'view' => ViewConductors::route('/{record}'),
            // 'edit' => EditConductors::route('/{record}/edit'),
        ];
    }
}
