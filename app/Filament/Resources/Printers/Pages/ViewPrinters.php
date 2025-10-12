<?php

namespace App\Filament\Resources\Printers\Pages;

use App\Filament\Resources\Printers\PrintersResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrinters extends ViewRecord
{
    protected static string $resource = PrintersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
