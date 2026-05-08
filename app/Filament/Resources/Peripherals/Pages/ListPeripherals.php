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

            'CHARGER' => Tab::make('CHARGER')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Charger', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Charger', 'and')->count('*')),

            'DOCKING STATION' => Tab::make('DOCKING STATION')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Docking Station', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Docking Station', 'and')->count('*')),

            'MICROPHONE/SPEAKER' => Tab::make('MICROPHONE/SPEAKER')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Microphone/Speaker', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Microphone/Speaker', 'and')->count('*')),

            'ROUTER' => Tab::make('ROUTER')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Router', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Router', 'and')->count('*')),

            'SWITCH' => Tab::make('SWITCH')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Switch', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Switch', 'and')->count('*')),

            'PROJECTOR' => Tab::make('PROJECTOR')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Projector', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Projector', 'and')->count('*')),

            'OTHER' => Tab::make('OTHER')
                ->modifyQueryUsing(fn ($query) => $query->where('item_type', '=', 'Other', 'and'))
                ->badge(fn () => Peripherals::where('item_type', '=', 'Other', 'and')->count('*')),
        ];
    }
}
