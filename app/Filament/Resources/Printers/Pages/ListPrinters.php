<?php

namespace App\Filament\Resources\Printers\Pages;

use App\Filament\Resources\Printers\PrintersResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\Printer;

class ListPrinters extends ListRecords
{
    protected static string $resource = PrintersResource::class;

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
                ->badge(fn () => Printer::where('department', 'MIS')->count()),

            'HR' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'HR');})
                ->badge(fn () => Printer::where('department', 'HR')->count()),

            'OPERATIONS' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Operations');})
                ->badge(fn () => Printer::where('department', 'Operations')->count()),

            'PRODUCTION' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Production');})
                ->badge(fn () => Printer::where('department', 'Production')->count()),

            'ACCOUNTING' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Accounting');})
                ->badge(fn () => Printer::where('department', 'Accounting')->count()),

            'CASH' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Cash');})
                ->badge(fn () => Printer::where('department', 'Cash')->count()),

            'CLINIC' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('department', 'Clinic');})
                ->badge(fn () => Printer::where('department', 'Clinic')->count()),
        ];
    }
}
