<?php

namespace App\Filament\Resources\DispatchSheets\Pages;

use App\Filament\Resources\DispatchSheets\DispatchSheetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDispatchSheet extends EditRecord
{
    protected static string $resource = DispatchSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            // DeleteAction::make(),
        ];
    }
}
