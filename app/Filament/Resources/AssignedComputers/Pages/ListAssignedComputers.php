<?php

namespace App\Filament\Resources\AssignedComputers\Pages;

use App\Filament\Resources\AssignedComputers\AssignedComputerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssignedComputers extends ListRecords
{
    protected static string $resource = AssignedComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
