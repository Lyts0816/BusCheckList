<?php

namespace App\Filament\Resources\TurnOvers\Pages;

use App\Filament\Resources\TurnOvers\TurnOverResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTurnOver extends EditRecord
{
    protected static string $resource = TurnOverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
