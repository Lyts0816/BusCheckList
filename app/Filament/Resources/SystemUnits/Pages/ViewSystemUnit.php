<?php

namespace App\Filament\Resources\SystemUnits\Pages;

use App\Filament\Resources\SystemUnits\SystemUnitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSystemUnit extends ViewRecord
{
    protected static string $resource = SystemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
