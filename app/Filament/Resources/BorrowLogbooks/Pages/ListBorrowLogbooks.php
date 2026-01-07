<?php

namespace App\Filament\Resources\BorrowLogbooks\Pages;

use App\Filament\Resources\BorrowLogbooks\BorrowLogbookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\BorrowLogbook;

class ListBorrowLogbooks extends ListRecords
{
    protected static string $resource = BorrowLogbookResource::class;

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
                ->badge(fn () => BorrowLogbook::count()),

            'Borrowed' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('status', 'Borrowed');})
                ->badge(fn () => BorrowLogbook::where('status', 'Borrowed')->count()),

            'Returned' => Tab::make()
                ->modifyQueryUsing(function ($query) {$query->where('status', 'Returned');})
                ->badge(fn () => BorrowLogbook::where('status', 'Returned')->count()),

            
        ];
    }
}
