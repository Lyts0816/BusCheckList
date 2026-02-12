<?php

namespace App\Filament\Resources\BusNumbers\Pages;

use App\Filament\Resources\BusNumbers\BusNumberResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBusNumber extends EditRecord
{
    protected static string $resource = BusNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            // DeleteAction::make(),
        ];
    }
}
