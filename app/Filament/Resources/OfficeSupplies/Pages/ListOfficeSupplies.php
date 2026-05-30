<?php

namespace App\Filament\Resources\OfficeSupplies\Pages;

use App\Filament\Resources\OfficeSupplies\OfficeSuppliesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfficeSupplies extends ListRecords
{
    protected static string $resource = OfficeSuppliesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Supply'),
        ];
    }
}
