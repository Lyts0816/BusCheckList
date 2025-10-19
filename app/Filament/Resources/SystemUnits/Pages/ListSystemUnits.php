<?php

namespace App\Filament\Resources\SystemUnits\Pages;

use App\Filament\Resources\SystemUnits\SystemUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\SystemUnit;


class ListSystemUnits extends ListRecords
{
    protected static string $resource = SystemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [

            'ALL' => Tab::make()
                ->label('All SYSTEM UNITS')
                ->modifyQueryUsing(function ($query) {$query->whereNotNull('id');})
                ->badge(fn () => SystemUnit::count()),
        ];
            
    }
}
