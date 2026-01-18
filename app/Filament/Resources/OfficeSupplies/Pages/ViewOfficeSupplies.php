<?php

namespace App\Filament\Resources\OfficeSupplies\Pages;

use App\Filament\Resources\OfficeSupplies\OfficeSuppliesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOfficeSupplies extends ViewRecord
{
    protected static string $resource = OfficeSuppliesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
