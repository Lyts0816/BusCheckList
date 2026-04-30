<?php

namespace App\Filament\Resources\TurnOvers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TurnOverInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('from_department'),
                TextEntry::make('to_department'),
                TextEntry::make('current_date')
                    ->date(),
                TextEntry::make('printed_date')
                    ->date(),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('particulars'),
                TextEntry::make('serial_number'),
                TextEntry::make('recipient'),
                TextEntry::make('recipient_department_head'),
                TextEntry::make('endorser'),
                TextEntry::make('endorser_department_head'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
