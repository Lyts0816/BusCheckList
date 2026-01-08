<?php

namespace App\Filament\Resources\BorrowLogs\Pages;

use App\Filament\Resources\BorrowLogs\BorrowLogsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBorrowLogs extends ListRecords
{
    protected static string $resource = BorrowLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
