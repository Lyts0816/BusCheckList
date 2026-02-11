<?php

namespace App\Filament\Resources\DispatchSheets;

use App\Filament\Resources\DispatchSheets\Pages\CreateDispatchSheet;
use App\Filament\Resources\DispatchSheets\Pages\EditDispatchSheet;
use App\Filament\Resources\DispatchSheets\Pages\ListDispatchSheets;
use App\Filament\Resources\DispatchSheets\Pages\ViewDispatchSheet;
use App\Filament\Resources\DispatchSheets\Schemas\DispatchSheetForm;
use App\Filament\Resources\DispatchSheets\Schemas\DispatchSheetInfolist;
use App\Filament\Resources\DispatchSheets\Tables\DispatchSheetsTable;
use App\Filament\Resources\DispatchSheets\RelationManagers\TripsRelationManager;
use App\Models\DispatchSheet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DispatchSheetResource extends Resource
{
    protected static ?string $model = DispatchSheet::class;
    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Dispatch Header';

    public static function form(Schema $schema): Schema
    {
        return DispatchSheetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DispatchSheetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispatchSheetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TripsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDispatchSheets::route('/'),
            'create' => CreateDispatchSheet::route('/create'),
            'view' => ViewDispatchSheet::route('/{record}'),
            'edit' => EditDispatchSheet::route('/{record}/edit'),
        ];
    }
}
