<?php

namespace App\Filament\Resources\BusClasses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BusClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('class_name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('remarks'),
            ]);
    }
}
