<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RoutesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRoutes extends ViewRecord
{
    protected static string $resource = RoutesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
