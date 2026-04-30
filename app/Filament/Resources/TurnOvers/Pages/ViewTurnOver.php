<?php

namespace App\Filament\Resources\TurnOvers\Pages;

use App\Filament\Resources\TurnOvers\TurnOverResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTurnOver extends ViewRecord
{
    protected static string $resource = TurnOverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
