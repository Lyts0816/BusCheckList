<?php

namespace App\Filament\Resources\Peripherals\Pages;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPeripherals extends EditRecord
{
    protected static string $resource = PeripheralsResource::class;

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
