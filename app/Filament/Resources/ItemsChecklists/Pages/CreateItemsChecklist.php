<?php

namespace App\Filament\Resources\ItemsChecklists\Pages;

use App\Filament\Resources\ItemsChecklists\ItemsChecklistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateItemsChecklist extends CreateRecord
{
    protected static string $resource = ItemsChecklistResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
