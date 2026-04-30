<?php

namespace App\Filament\Resources\TurnOvers\Pages;

use App\Filament\Resources\TurnOvers\TurnOverResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTurnOvers extends ListRecords
{
    protected static string $resource = TurnOverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
