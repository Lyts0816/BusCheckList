<?php

namespace App\Filament\Resources\LeaveLogs\Pages;

use App\Filament\Resources\LeaveLogs\LeaveLogResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListLeaveLogs extends ListRecords
{
    protected static string $resource = LeaveLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('7xl')
                ->label('New Leave Record'),

            Action::make('exportAllExcel')
                ->label('Export All')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('export.leave-logs.all-excel'))
                ->openUrlInNewTab(),
        ];
    }
}
