<?php

namespace App\Filament\Resources\Conductors\Pages;

use App\Filament\Resources\Conductors\ConductorsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConductors extends EditRecord
{
    protected static string $resource = ConductorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
