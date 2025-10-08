<?php

namespace App\Filament\Resources\Peripherals\Pages;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ListPeripherals extends ListRecords
{
    protected static string $resource = PeripheralsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),


        ];
    }

    public function getTabs(): array
    {
        return [


            'KEYBOARD' => Tab::make()->modifyQueryUsing(function ($query) {
                $query->where('item_type', 'KEYBOARD');
            }),
            'MOUSE' => Tab::make()->modifyQueryUsing(function ($query) {
                $query->where('item_type', 'MOUSE');
            }),
            'MONITOR' => Tab::make()->modifyQueryUsing(function ($query) {
                $query->where('item_type', 'MONITOR');
            }),
            'UPS' => Tab::make()->modifyQueryUsing(function ($query) {
                $query->where('item_type', 'UPS');
            }),
        ];
    }
}
