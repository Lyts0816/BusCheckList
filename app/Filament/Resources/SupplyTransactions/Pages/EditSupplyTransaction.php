<?php

namespace App\Filament\Resources\SupplyTransactions\Pages;

use App\Filament\Resources\SupplyTransactions\SupplyTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplyTransaction extends EditRecord
{
    protected static string $resource = SupplyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
