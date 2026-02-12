<?php

namespace App\Filament\Resources\DispatchSheets\Pages;

use App\Filament\Resources\DispatchSheets\DispatchSheetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDispatchSheet extends CreateRecord
{
    protected static string $resource = DispatchSheetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
