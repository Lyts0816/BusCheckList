<?php

namespace App\Filament\Resources\ItemsChecklists\Pages;

use App\Filament\Resources\ItemsChecklists\ItemsChecklistResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditItemsChecklist extends EditRecord
{
    protected static string $resource = ItemsChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
