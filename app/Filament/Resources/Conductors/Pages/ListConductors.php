<?php

namespace App\Filament\Resources\Conductors\Pages;

use App\Filament\Resources\Conductors\ConductorsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConductors extends ListRecords
{
    protected static string $resource = ConductorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Conductor')
                ->modalHeading('Create New Conductor'),
        ];
    }
}
