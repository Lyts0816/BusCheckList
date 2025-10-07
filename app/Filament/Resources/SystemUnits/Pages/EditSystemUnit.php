<?php

namespace App\Filament\Resources\SystemUnits\Pages;

use App\Filament\Resources\SystemUnits\SystemUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSystemUnit extends EditRecord
{
    protected static string $resource = SystemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
