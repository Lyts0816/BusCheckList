<?php

namespace App\Filament\Resources\Routes;

use App\Filament\Resources\Routes\Pages\CreateRoutes;
use App\Filament\Resources\Routes\Pages\EditRoutes;
use App\Filament\Resources\Routes\Pages\ListRoutes;
use App\Filament\Resources\Routes\Pages\ViewRoutes;
use App\Filament\Resources\Routes\Schemas\RoutesForm;
use App\Filament\Resources\Routes\Schemas\RoutesInfolist;
use App\Filament\Resources\Routes\Tables\RoutesTable;
use App\Models\Routes;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RoutesResource extends Resource
{
    protected static ?string $model = Routes::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $recordTitleAttribute = 'Bus Numbers';

    protected static UnitEnum|string|null $navigationGroup = 'DISPATCH TRIPS';

    public static function form(Schema $schema): Schema
    {
        return RoutesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoutesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoutesTable::configure($table);
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
            'index' => ListRoutes::route('/'),
            // 'create' => CreateRoutes::route('/create'),
            // 'view' => ViewRoutes::route('/{record}'),
            // 'edit' => EditRoutes::route('/{record}/edit'),
        ];
    }
}
