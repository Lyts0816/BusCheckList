<?php

namespace App\Filament\Resources\BorrowLogbooks\Pages;

use App\Filament\Resources\BorrowLogbooks\BorrowLogbookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBorrowLogbooks extends ListRecords
{
    protected static string $resource = BorrowLogbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
