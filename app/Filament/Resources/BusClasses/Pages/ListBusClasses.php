<?php

namespace App\Filament\Resources\BusClasses\Pages;

use App\Filament\Resources\BusClasses\BusClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusClasses extends ListRecords
{
    protected static string $resource = BusClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
