<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RoutesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRoutes extends EditRecord
{
    protected static string $resource = RoutesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
