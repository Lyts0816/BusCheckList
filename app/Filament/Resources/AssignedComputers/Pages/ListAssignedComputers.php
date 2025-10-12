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

            'MIS' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'MIS');})
                ->badge(fn () => AssignedComputer::where('department', 'MIS')->count()),

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
