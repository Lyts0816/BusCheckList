<?php

namespace App\Filament\Resources\BorrowLogbooks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BorrowLogbookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('borrow_date')
                    ->date(),
                TextEntry::make('borrower_name'),
                TextEntry::make('department'),
                TextEntry::make('equipment'),
                TextEntry::make('item_asset_code'),
                TextEntry::make('department_head_name'),
                TextEntry::make('purpose_borrowing'),
                TextEntry::make('handled_by'),
                TextEntry::make('date_returned')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
