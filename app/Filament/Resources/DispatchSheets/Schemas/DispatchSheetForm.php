<?php

namespace App\Filament\Resources\DispatchSheets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;

class DispatchSheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FusedGroup::make([
                DatePicker::make('dispatch_date')
                    ->autofocus()
                    ->native(true)
                    ->format('Y-m-d') // human
                    ->closeOnDateSelection(),

                // Select::make('country')
                //     ->placeholder('Country')
                //     ->options([
                //         // ...
                //     ]),

                ])->label('Date'),
            ]);
    }
}
