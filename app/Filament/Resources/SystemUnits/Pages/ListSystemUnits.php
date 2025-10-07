<?php

namespace App\Filament\Resources\SystemUnits\Pages;

use App\Filament\Resources\SystemUnits\SystemUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSystemUnits extends ListRecords
{
    protected static string $resource = SystemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
