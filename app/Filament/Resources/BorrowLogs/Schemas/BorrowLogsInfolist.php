<?php

namespace App\Filament\Resources\BorrowLogs\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

class BorrowLogsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


                TextEntry::make('borrower_name')
                    ->label('Borrower'),

                TextEntry::make('department'),

                TextEntry::make('borrowed_date')
                    ->label('Date Borrowed')
                    ->date(),

                TextEntry::make('department_head_name')
                    ->label('Department Head'),

                TextEntry::make('purpose_borrowing')
                    ->label('Purpose'),

                TextEntry::make('handled_by')
                    ->label('Handled By'),

                RepeatableEntry::make('items')
                    ->label('Borrowed Items')
                    ->schema([
                        TextEntry::make('item_name')
                            ->label('Item Name'),

                        TextEntry::make('item_asset_code')
                            ->label('Asset Code'),

                        TextEntry::make('quantity')
                            ->label('Quantity'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->colors([
                                'success' => 'Returned',
                                'danger' => 'Borrowed',
                            ]),

                        TextEntry::make('return_date')
                            ->label('Return Date')
                            ->date(),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),




            ]);
    }
}
