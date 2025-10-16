<?php

namespace App\Filament\Resources\BorrowLogbooks\Pages;

use App\Filament\Resources\BorrowLogbooks\BorrowLogbookResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBorrowLogbook extends EditRecord
{
    protected static string $resource = BorrowLogbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
