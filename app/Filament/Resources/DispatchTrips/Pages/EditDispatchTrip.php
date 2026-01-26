<?php

namespace App\Filament\Resources\DispatchTrips\Pages;

use App\Filament\Resources\DispatchTrips\DispatchTripResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDispatchTrip extends EditRecord
{
    protected static string $resource = DispatchTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
