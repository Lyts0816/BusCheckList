<?php

namespace App\Filament\Resources\AssetMaintenanceLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class AssetMaintenanceLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('maintainable_type')
                        
                    ->formatStateUsing(function (?string $state): ?string {
                        if (! $state) {
                            return null;
                        }

                        $baseName = class_basename($state);

                        return trim(preg_replace('/(?<!^)([A-Z])/', ' $1', $baseName));
                    }),

                TextEntry::make('maintainable_id')
                    ->columnSpan(1)
                    ->getStateUsing(fn ($record) => data_get($record, 'maintainable.asset_code')
                        ?? data_get($record, 'maintainable.name')
                        ?? $record->maintainable_id)
                    ->numeric(),

                TextEntry::make('component.name')
                    ->label('Component')
                    ->columnSpan(1)
                    ->numeric(),

                TextEntry::make('maintenance_type')
                    ->columnSpan(1)
                    ->badge(),

                TextEntry::make('maintenance_date')
                    ->columnSpan(1)
                    ->date(),

                TextEntry::make('performed_by')
                    ->columnSpan(1),

                TextEntry::make('cost')
                    ->money('php'),

                Section::make('')
                    ->schema([
                    TextEntry::make('issue_reported')
                    ->wrap(),
                
                    ])->columnSpanFull(),

                Section::make('')
                    ->schema([
                TextEntry::make('action_taken')
                    ->columnSpanFull()
                    ->wrap(),
                
                ])->columnSpanFull(),

            ])->columns(6);
    }
}
