<?php

namespace App\Filament\Resources\AssignedComputers\Pages;

use App\Filament\Resources\AssignedComputers\AssignedComputerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\AssignedComputer;

class ListAssignedComputers extends ListRecords
{
    protected static string $resource = AssignedComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->label('All')
                ->badge(fn () => AssignedComputer::count()),

            'TERMINAL' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'TERMINAL');})
                ->badge(fn () => AssignedComputer::where('department', 'TERMINAL')->count()),

            'STOCK ROOM' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'STOCK ROOM');})
                ->badge(fn () => AssignedComputer::where('department', 'STOCK ROOM')->count()),

            'MIS' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'MIS');})
                ->badge(fn () => AssignedComputer::where('department', 'MIS')->count()),

            'AUDIT' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'AUDIT');})
                ->badge(fn () => AssignedComputer::where('department', 'AUDIT')->count()),
                
            'HR' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'HR');})
                ->badge(fn () => AssignedComputer::where('department', 'HR')->count()),

            'OPERATIONS' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Operations');})
                ->badge(fn () => AssignedComputer::where('department', 'Operations')->count()),

            'PRODUCTION' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Production');})
                ->badge(fn () => AssignedComputer::where('department', 'Production')->count()),

            'ACCOUNTING' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Accounting');})
                ->badge(fn () => AssignedComputer::where('department', 'Accounting')->count()),

            'CASH' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Cash');})
                ->badge(fn () => AssignedComputer::where('department', 'Cash')->count()),

            'CLINIC' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Clinic');})
                ->badge(fn () => AssignedComputer::where('department', 'Clinic')->count()),
        ];
    }
}
