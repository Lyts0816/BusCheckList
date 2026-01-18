<?php

namespace App\Filament\Resources\SupplyTransactions\Pages;

use App\Filament\Resources\SupplyTransactions\SupplyTransactionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplyTransaction extends ViewRecord
{
    protected static string $resource = SupplyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
