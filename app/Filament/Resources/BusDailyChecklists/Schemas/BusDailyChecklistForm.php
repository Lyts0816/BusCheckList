<?php

namespace App\Filament\Resources\BusDailyChecklists\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class BusDailyChecklistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bus_id')
                    ->label('Bus Number')
                    ->relationship('bus', 'bus_number')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('check_date')
                    ->default(now())
                    ->required(),

                Toggle::make('checked')
                    ->required(),
                    
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
