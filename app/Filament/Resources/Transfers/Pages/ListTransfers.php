<?php

namespace App\Filament\Resources\Transfers\Pages;

use App\Filament\Resources\Transfers\TransferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use App\Models\Transfer;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransfers extends ListRecords
{
    protected static string $resource = TransferResource::class;

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
        'all' => Tab::make('All'),

        'today' => Tab::make('Today')
            ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('date', today()))
            ->badge(Transfer::whereDate('date', today())->count()),

        'week' => Tab::make('This week')
            ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])),

        'pending' => Tab::make('Pending')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Pending'))
            ->badge(Transfer::where('status', 'Pending')->count())
            ->badgeColor('warning'),

        'transferred' => Tab::make('Transferred')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Transferred')),

        'cancelled' => Tab::make('Cancelled')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Cancelled')),
    ];
}
}
