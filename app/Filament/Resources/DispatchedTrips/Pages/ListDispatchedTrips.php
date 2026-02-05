<?php

namespace App\Filament\Resources\DispatchedTrips\Pages;

use App\Filament\Resources\DispatchedTrips\DispatchedTripsResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AssetImport;

class ListDispatchedTrips extends ListRecords
{
    protected static string $resource = DispatchedTripsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()

        ];
    }
}
