<?php

namespace App\Filament\Resources\AssignedComputers\Pages;

use App\Filament\Resources\AssignedComputers\AssignedComputerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAssignedComputer extends EditRecord
{
    protected static string $resource = AssignedComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
