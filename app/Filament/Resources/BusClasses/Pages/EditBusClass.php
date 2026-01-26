<?php

namespace App\Filament\Resources\BusClasses\Pages;

use App\Filament\Resources\BusClasses\BusClassResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBusClass extends EditRecord
{
    protected static string $resource = BusClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
