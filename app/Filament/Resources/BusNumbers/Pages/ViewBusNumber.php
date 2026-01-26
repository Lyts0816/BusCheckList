<?php

namespace App\Filament\Resources\BusNumbers\Pages;

use App\Filament\Resources\BusNumbers\BusNumberResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBusNumber extends ViewRecord
{
    protected static string $resource = BusNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
