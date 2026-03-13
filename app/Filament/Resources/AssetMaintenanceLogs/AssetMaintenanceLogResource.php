<?php

namespace App\Filament\Resources\AssetMaintenanceLogs;

use App\Filament\Resources\AssetMaintenanceLogs\Pages\CreateAssetMaintenanceLog;
use App\Filament\Resources\AssetMaintenanceLogs\Pages\EditAssetMaintenanceLog;
use App\Filament\Resources\AssetMaintenanceLogs\Pages\ListAssetMaintenanceLogs;
use App\Filament\Resources\AssetMaintenanceLogs\Pages\ViewAssetMaintenanceLog;
use App\Filament\Resources\AssetMaintenanceLogs\Schemas\AssetMaintenanceLogForm;
use App\Filament\Resources\AssetMaintenanceLogs\Schemas\AssetMaintenanceLogInfolist;
use App\Filament\Resources\AssetMaintenanceLogs\Tables\AssetMaintenanceLogsTable;
use App\Models\AssetMaintenanceLog;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetMaintenanceLogResource extends Resource
{
    protected static ?string $model = AssetMaintenanceLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $recordTitleAttribute = 'Assign Computer';

    protected static UnitEnum|string|null $navigationGroup = 'MAINTENANCE LOGS';

    protected static ?string $navigationLabel = 'Maintenance Logs';

    public static function form(Schema $schema): Schema
    {
        return AssetMaintenanceLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssetMaintenanceLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetMaintenanceLogsTable::configure($table);
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
            'index' => ListAssetMaintenanceLogs::route('/'),
            // 'create' => CreateAssetMaintenanceLog::route('/create'),
            // 'view' => ViewAssetMaintenanceLog::route('/{record}'),
            // 'edit' => EditAssetMaintenanceLog::route('/{record}/edit'),
        ];
    }
}
