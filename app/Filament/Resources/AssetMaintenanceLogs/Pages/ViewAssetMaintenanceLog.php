<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Pages;

use App\Filament\Resources\AssetMaintenanceLogs\AssetMaintenanceLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssetMaintenanceLog extends ViewRecord
{
    protected static string $resource = AssetMaintenanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
