<?php

namespace App\Filament\Resources\BorrowLogbooks\Pages;

use App\Filament\Resources\BorrowLogbooks\BorrowLogbookResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBorrowLogbook extends ViewRecord
{
    protected static string $resource = BorrowLogbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
