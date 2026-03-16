<?php

namespace App\Filament\Resources\LeaveLogs\Pages;

use App\Filament\Resources\LeaveLogs\LeaveLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveLogs extends ListRecords
{
    protected static string $resource = LeaveLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
             ->label('New Leave Record'),
        ];
    }
}
