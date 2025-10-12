<?php

namespace App\Filament\Resources\BusDailyChecklists\Pages;

use App\Filament\Resources\BusDailyChecklists\BusDailyChecklistResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBusDailyChecklist extends EditRecord
{
    protected static string $resource = BusDailyChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
