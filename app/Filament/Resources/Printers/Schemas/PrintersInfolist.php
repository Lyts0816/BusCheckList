<?php

namespace App\Filament\Resources\Printers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PrintersInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('department')
                    ->label('Department'),
                TextEntry::make('printer_host')
                    ->label('ID'),
                TextEntry::make('printer_model')
                    ->label('Printer Model'),
                TextEntry::make('printer_asset_code')
                    ->label('Printer Asset Code'),
                TextEntry::make('printer_serial_number')
                    ->label('Printer Serial Number'),
                TextEntry::make('date_acquired')
                    ->label('Date Acquired')
                    ->date(),
                TextEntry::make('description')
                    ->label('Description'),
            ]);
    }
}
