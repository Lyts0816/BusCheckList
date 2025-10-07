<?php

namespace App\Filament\Resources\AssignedComputers\Pages;

use App\Filament\Resources\AssignedComputers\AssignedComputerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignedComputer extends ViewRecord
{
    protected static string $resource = AssignedComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
