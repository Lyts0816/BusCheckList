<?php

namespace App\Filament\Resources\DispatchSheets\Pages;

use App\Filament\Resources\DispatchSheets\DispatchSheetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDispatchSheet extends ViewRecord
{
    protected static string $resource = DispatchSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
