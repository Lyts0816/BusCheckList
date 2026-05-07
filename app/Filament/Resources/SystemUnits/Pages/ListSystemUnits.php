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
            CreateAction::make()
            ->modalWidth('7xl'),
        ];
    }

    public function getTabs(): array
    {
        return [

            'ALL' => Tab::make('ALL')
                ->label('All')
                ->modifyQueryUsing(function ($query) {$query->whereNotNull('id', 'and');})
                ->badge(fn () => SystemUnit::count('*')),

            'SYSTEM UNIT' => Tab::make('SYSTEM UNIT')
                ->modifyQueryUsing(function ($query) {$query->where('asset_type', '=', 'System Unit', 'and');})
                ->badge(fn () => SystemUnit::where('asset_type', '=', 'System Unit', 'and')->count('*')),

            'LAPTOP' => Tab::make('LAPTOP')
                ->modifyQueryUsing(function ($query) {$query->where('asset_type', '=', 'Laptop', 'and');})
                ->badge(fn () => SystemUnit::where('asset_type', '=', 'Laptop', 'and')->count('*')),
        ];
            
    }
}
