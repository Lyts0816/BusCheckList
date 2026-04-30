<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(200)
                    ->required(),

                TextInput::make('office_location')
                    ->maxLength(255)
                    ->required(),

                TextInput::make('description')
                    ->maxLength(255),
            ]);
    }
}
