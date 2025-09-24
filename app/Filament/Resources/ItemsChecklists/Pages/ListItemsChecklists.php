<?php

namespace App\Filament\Resources\ItemsChecklists\Pages;

use App\Filament\Resources\ItemsChecklists\ItemsChecklistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItemsChecklists extends ListRecords
{
    protected static string $resource = ItemsChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
