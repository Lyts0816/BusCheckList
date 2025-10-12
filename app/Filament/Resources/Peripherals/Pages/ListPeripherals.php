<?php

namespace App\Filament\Resources\Peripherals\Pages;

use App\Filament\Resources\Peripherals\PeripheralsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\Peripherals;


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

            'KEYBOARD' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('item_type', 'KEYBOARD');})
                ->badge(fn () => Peripherals::where('item_type', 'Keyboard')->count()),

            'MOUSE' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('item_type', 'MOUSE');})
                ->badge(fn () => Peripherals::where('item_type', 'Mouse')->count()),

            'MONITOR' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('item_type', 'MONITOR');})
                ->badge(fn () => Peripherals::where('item_type', 'Monitor')->count()),

            'UPS' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('item_type', 'UPS');})
                ->badge(fn () => Peripherals::where('item_type', 'UPS')->count()),
        ];
    }
}
