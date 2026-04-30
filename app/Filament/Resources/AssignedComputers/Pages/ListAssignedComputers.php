<?php

namespace App\Filament\Resources\AssignedComputers\Pages;

use App\Filament\Resources\AssignedComputers\AssignedComputerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\AssignedComputer;
use App\Models\Departments;

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
        // Get all departments mapped by name
        $deptMap = Departments::pluck('id', 'name')->toArray();

        $allCount = AssignedComputer::query()->count('*');
        $terminalId = $deptMap['TERMINAL'] ?? null;
        $terminalCount = $terminalId ? AssignedComputer::query()->where('department_id', '=', $terminalId)->count('*') : 0;

        $stockRoomId = $deptMap['STOCK ROOM'] ?? null;
        $stockRoomCount = $stockRoomId ? AssignedComputer::query()->where('department_id', '=', $stockRoomId)->count('*') : 0;

        $misId = $deptMap['MIS'] ?? null;
        $misCount = $misId ? AssignedComputer::query()->where('department_id', '=', $misId)->count('*') : 0;

        $auditId = $deptMap['AUDIT'] ?? null;
        $auditCount = $auditId ? AssignedComputer::query()->where('department_id', '=', $auditId)->count('*') : 0;

        $hrId = $deptMap['HR'] ?? null;
        $hrCount = $hrId ? AssignedComputer::query()->where('department_id', '=', $hrId)->count('*') : 0;

        $operationsId = $deptMap['Operations'] ?? null;
        $operationsCount = $operationsId ? AssignedComputer::query()->where('department_id', '=', $operationsId)->count('*') : 0;

        $productionId = $deptMap['Production'] ?? null;
        $productionCount = $productionId ? AssignedComputer::query()->where('department_id', '=', $productionId)->count('*') : 0;

        $accountingId = $deptMap['Accounting'] ?? null;
        $accountingCount = $accountingId ? AssignedComputer::query()->where('department_id', '=', $accountingId)->count('*') : 0;

        $cashId = $deptMap['Cash'] ?? null;
        $cashCount = $cashId ? AssignedComputer::query()->where('department_id', '=', $cashId)->count('*') : 0;

        $clinicId = $deptMap['Clinic'] ?? null;
        $clinicCount = $clinicId ? AssignedComputer::query()->where('department_id', '=', $clinicId)->count('*') : 0;

        return [
            Tab::make('All')
                ->label('All')
                ->badge($allCount),

            'TERMINAL' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $terminalId ? $query->where('department_id', '=', $terminalId) : $query)
                ->badge($terminalCount),

            'STOCK ROOM' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $stockRoomId ? $query->where('department_id', '=', $stockRoomId) : $query)
                ->badge($stockRoomCount),

            'MIS' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $misId ? $query->where('department_id', '=', $misId) : $query)
                ->badge($misCount),

            'AUDIT' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $auditId ? $query->where('department_id', '=', $auditId) : $query)
                ->badge($auditCount),
                
            'HR' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $hrId ? $query->where('department_id', '=', $hrId) : $query)
                ->badge($hrCount),

            'OPERATIONS' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $operationsId ? $query->where('department_id', '=', $operationsId) : $query)
                ->badge($operationsCount),

            'PRODUCTION' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $productionId ? $query->where('department_id', '=', $productionId) : $query)
                ->badge($productionCount),

            'ACCOUNTING' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $accountingId ? $query->where('department_id', '=', $accountingId) : $query)
                ->badge($accountingCount),

            'CASH' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $cashId ? $query->where('department_id', '=', $cashId) : $query)
                ->badge($cashCount),

            'CLINIC' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $clinicId ? $query->where('department_id', '=', $clinicId) : $query)
                ->badge($clinicCount),
        ];
    }
}
