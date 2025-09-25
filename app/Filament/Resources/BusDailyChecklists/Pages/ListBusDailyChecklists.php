<?php

namespace App\Filament\Resources\BusDailyChecklists\Pages;

use App\Filament\Resources\BusDailyChecklists\BusDailyChecklistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusDailyChecklists extends ListRecords
{
    protected static string $resource = BusDailyChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
