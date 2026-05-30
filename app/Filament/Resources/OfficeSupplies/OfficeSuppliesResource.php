<?php

namespace App\Filament\Resources\OfficeSupplies;

use App\Filament\Resources\OfficeSupplies\Pages\CreateOfficeSupplies;
use App\Filament\Resources\OfficeSupplies\Pages\EditOfficeSupplies;
use App\Filament\Resources\OfficeSupplies\Pages\ListOfficeSupplies;
use App\Filament\Resources\OfficeSupplies\Pages\ViewOfficeSupplies;
use App\Filament\Resources\OfficeSupplies\Schemas\OfficeSuppliesForm;
use App\Filament\Resources\OfficeSupplies\Schemas\OfficeSuppliesInfolist;
use App\Filament\Resources\OfficeSupplies\Tables\OfficeSuppliesTable;
use App\Models\OfficeSupplies;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;


class OfficeSuppliesResource extends Resource
{
    protected static ?string $model = OfficeSupplies::class;

    protected static ?string $modelLabel = 'Supply';

    protected static ?string $pluralModelLabel = 'Supplies Inventory';

    protected static UnitEnum|string|null $navigationGroup = 'SUPPLIES INVENTORY';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Supplies Inventory';

    public static function form(Schema $schema): Schema
    {
        return OfficeSuppliesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OfficeSuppliesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficeSuppliesTable::configure($table);
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
            'index' => ListOfficeSupplies::route('/'),
            // 'create' => CreateOfficeSupplies::route('/create'),
            // 'view' => ViewOfficeSupplies::route('/{record}'),
            // 'edit' => EditOfficeSupplies::route('/{record}/edit'),
        ];
    }
}
