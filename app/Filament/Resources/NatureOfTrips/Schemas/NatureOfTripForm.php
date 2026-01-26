<?php

namespace App\Filament\Resources\NatureOfTrips\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NatureOfTripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nature_of_trip_name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('remarks'),
            ]);
    }
}
