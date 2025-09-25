<?php

namespace App\Filament\Resources\BusDailyChecklists\Pages;

use App\Filament\Resources\BusDailyChecklists\BusDailyChecklistResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBusDailyChecklist extends ViewRecord
{
    protected static string $resource = BusDailyChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
