<?php

namespace App\Filament\Resources\Printers\Pages;

use App\Filament\Resources\Printers\PrintersResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrinters extends CreateRecord
{
    protected static string $resource = PrintersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
