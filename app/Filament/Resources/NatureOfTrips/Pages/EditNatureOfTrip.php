<?php

namespace App\Filament\Resources\NatureOfTrips\Pages;

use App\Filament\Resources\NatureOfTrips\NatureOfTripResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNatureOfTrip extends EditRecord
{
    protected static string $resource = NatureOfTripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
