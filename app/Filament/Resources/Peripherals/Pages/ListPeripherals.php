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

            'ALL' => Tab::make('ALL')
                ->label('All PERIPHERALS')
                ->modifyQueryUsing(function ($query) {$query->whereNotNull('id');})
                ->badge(fn () => Peripherals::count('*')),
            'KEYBOARD' => Tab::make('KEYBOARD')
                ->modifyQueryUsing(function ($query) {$query->where('item_type', '=', 'KEYBOARD', 'and');})
                ->badge(fn () => Peripherals::where('item_type', '=', 'Keyboard', 'and')->count('*')),

            'MOUSE' => Tab::make('MOUSE')
                ->modifyQueryUsing(function ($query) {$query->where('item_type', '=', 'MOUSE', 'and');})
                ->badge(fn () => Peripherals::where('item_type', '=', 'Mouse', 'and')->count('*')),

            'MONITOR' => Tab::make('MONITOR')
                ->modifyQueryUsing(function ($query) {$query->where('item_type', '=', 'MONITOR', 'and');})
                ->badge(fn () => Peripherals::where('item_type', '=', 'Monitor', 'and')->count('*')),

            'UPS' => Tab::make('UPS')
                ->modifyQueryUsing(function ($query) {$query->where('item_type', '=', 'UPS', 'and');})
                ->badge(fn () => Peripherals::where('item_type', '=', 'UPS', 'and')->count('*')),
        ];
    }
}
