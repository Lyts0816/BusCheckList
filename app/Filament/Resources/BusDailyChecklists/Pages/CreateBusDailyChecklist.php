<?php

namespace App\Filament\Resources\BusDailyChecklists\Pages;

use App\Filament\Resources\BusDailyChecklists\BusDailyChecklistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusDailyChecklist extends CreateRecord
{
    protected static string $resource = BusDailyChecklistResource::class;

        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
