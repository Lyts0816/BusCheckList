<?php

namespace App\Filament\Resources\ItemsChecklists\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemsChecklistInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item_type'),
                TextEntry::make('item_name'),
                TextEntry::make('bus_id')
                    ->numeric(),
                TextEntry::make('item_asset_code'),
                TextEntry::make('status'),
                TextEntry::make('date_checked')
                    ->date(),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
