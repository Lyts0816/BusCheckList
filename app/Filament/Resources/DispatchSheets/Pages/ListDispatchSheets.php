<?php

namespace App\Filament\Resources\DispatchSheets\Pages;

use App\Filament\Resources\DispatchSheets\DispatchSheetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDispatchSheets extends ListRecords
{
    protected static string $resource = DispatchSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
