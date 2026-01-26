<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RoutesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoutes extends ListRecords
{
    protected static string $resource = RoutesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
