<?php

namespace App\Filament\Resources\ItemsChecklists\Pages;

use App\Filament\Resources\ItemsChecklists\ItemsChecklistResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewItemsChecklist extends ViewRecord
{
    protected static string $resource = ItemsChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
