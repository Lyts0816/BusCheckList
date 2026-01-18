<?php

namespace App\Filament\Resources\OfficeSupplies\Pages;

use App\Filament\Resources\OfficeSupplies\OfficeSuppliesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficeSupplies extends EditRecord
{
    protected static string $resource = OfficeSuppliesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
