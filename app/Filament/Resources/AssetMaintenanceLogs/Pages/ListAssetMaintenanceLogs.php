<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Pages;

use App\Filament\Resources\AssetMaintenanceLogs\AssetMaintenanceLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetMaintenanceLogs extends ListRecords
{
    protected static string $resource = AssetMaintenanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Maintenance Log'),
        ];
    }
}
