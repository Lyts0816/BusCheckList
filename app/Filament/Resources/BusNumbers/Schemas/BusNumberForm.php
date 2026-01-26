<?php

namespace App\Filament\Resources\BusNumbers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class BusNumberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bus_number')->label('Bus Number')->required(),
                TextInput::make('bus_model')->label('Bus Model'),
                TextInput::make('bus_type')->label('Bus Type'),
                TextInput::make('seat_capacity')->label('Seat Capacity'),
            ]);
    }
}
