<?php

namespace App\Filament\Resources\BusNumbers\Pages;

use App\Filament\Resources\BusNumbers\BusNumberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusNumbers extends ListRecords
{
    protected static string $resource = BusNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
