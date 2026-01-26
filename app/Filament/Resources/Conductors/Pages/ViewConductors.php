<?php

namespace App\Filament\Resources\Conductors\Pages;

use App\Filament\Resources\Conductors\ConductorsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConductors extends ViewRecord
{
    protected static string $resource = ConductorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
