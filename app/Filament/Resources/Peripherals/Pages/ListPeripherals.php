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
                ->modifyQueryUsing(fn ($query) => $query->whereNotNull('id', 'and'))
                ->badge(fn () => Peripherals::count('*')),
            'KEYBOARD' => Tab::make('KEYBOARD')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Keyboard', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Keyboard', 'and')->count('*')),

            'MOUSE' => Tab::make('MOUSE')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Mouse', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Mouse', 'and')->count('*')),

            'MONITOR' => Tab::make('MONITOR')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Monitor', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Monitor', 'and')->count('*')),

            'UPS' => Tab::make('UPS')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'UPS', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'UPS', 'and')->count('*')),

            'HEADSET' => Tab::make('HEADSET')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Headset', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Headset', 'and')->count('*')),

            'WEBCAM' => Tab::make('WEBCAM')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Webcam', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Webcam', 'and')->count('*')),

            'CHARGE ADAPTER' => Tab::make('CHARGE ADAPTER')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Charge Adapter', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Charge Adapter', 'and')->count('*')),

            'DOCKING STATION' => Tab::make('DOCKING STATION')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Docking Station', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Docking Station', 'and')->count('*')),

            'OTHER' => Tab::make('OTHER')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Other', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Other', 'and')->count('*')),
        ];
    }
}
