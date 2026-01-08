<?php

namespace App\Filament\Resources\BorrowLogs\Pages;

use App\Filament\Resources\BorrowLogs\BorrowLogsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBorrowLogs extends ViewRecord
{
    protected static string $resource = BorrowLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
