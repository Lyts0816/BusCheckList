<?php

namespace App\Filament\Resources\Shipments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tracking_number'),
                TextEntry::make('barcode'),
                TextEntry::make('or_number'),
                TextEntry::make('origin_terminal'),
                TextEntry::make('destination_terminal'),
                TextEntry::make('sender_name'),
                TextEntry::make('sender_contact'),
                TextEntry::make('recipient_name'),
                TextEntry::make('recipient_contact'),
                TextEntry::make('box_number'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('weight')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('shipped_at')
                    ->dateTime()
                        ->dateTime('M d, Y h:i A'),
                TextEntry::make('arrived_at')
                    ->dateTime(),
                TextEntry::make('claimed_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
