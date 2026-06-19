<?php

namespace App\Filament\Resources\Transfers\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

class TransferInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('date')
                    ->label('Date Transferred'),

                TextEntry::make('status')
                    ->label('Status'),

                TextEntry::make('from')
                    ->label('From'),

                TextEntry::make('to')
                    ->label('To'),

                RepeatableEntry::make('items')
                    ->label('Transferred Items')
                    ->schema([
                        TextEntry::make('item_name')
                            ->label('Item Name'),

                        TextEntry::make('asset_code')
                            ->label('Asset Code'),

                        TextEntry::make('serial_number')
                            ->label('Serial Number'),

                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
