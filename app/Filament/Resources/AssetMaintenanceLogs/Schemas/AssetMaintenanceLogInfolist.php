<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssetMaintenanceLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('maintainable_type'),
                TextEntry::make('maintainable_id')
                    ->numeric(),
                TextEntry::make('component_id')
                    ->numeric(),
                TextEntry::make('maintenance_type'),
                TextEntry::make('maintenance_date')
                    ->date(),
                TextEntry::make('performed_by'),
                TextEntry::make('cost')
                    ->money(),
                TextEntry::make('next_maintenance')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
