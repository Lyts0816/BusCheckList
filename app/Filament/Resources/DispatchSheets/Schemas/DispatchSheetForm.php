<?php

namespace App\Filament\Resources\DispatchSheets\Schemas;

use App\Models\Routes;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DispatchSheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Section::make()->schema([
                FusedGroup::make([
                    DatePicker::make('dispatch_date')
                        ->autofocus()
                        ->placeholder('Select dispatch date')
                        ->required()
                        ->native(true)
                        ->format('Y-m-d')
                        ->closeOnDateSelection()
                        ->extraInputAttributes([
                            'autofocus' => 'autofocus',
                            'x-init' => '$nextTick(() => $el.focus())',
                        ])
                        ->columnSpan('full'),

                    Select::make('route_id')
                        ->placeholder('Select route')
                        ->required()
                        ->options(Routes::all()->mapWithKeys(
                            fn($route) => [$route->id => $route->from . ' - ' . $route->to]
                        ))
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get, $record) {
                            // Only set snapshot if record is new
                            if (!$record?->exists) {
                                $route = Routes::query()->whereKey($state)->first();
                                $set('origin', $route?->from);
                                $set('destination', $route?->to);
                                $set('route_snapshot_distance', $route?->distance);
                                $set('distance_at_dispatch', $route?->distance);
                            }
                        })
                        ->columnSpan(3),

                    TextInput::make('distance_at_dispatch')
                        ->placeholder('Km run')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(9999),

                ])->columns(4)->label('Dispatch Details')->columnSpanFull(),

                FusedGroup::make([
                    TextInput::make('origin')
                    ->hidden(true)
                        ->columnSpan(2)
                        ->placeholder('Origin')
                        ->readOnly(true)
                        ->dehydrated(),

                    TextInput::make('destination')
                        ->hidden(true)
                        ->columnSpan(2) 
                        ->placeholder('Destination')
                        ->readOnly(true)
                        ->dehydrated(),

                    TextInput::make('distance_at_dispatch')
                        // ->statePath('route_snapshot_distance')
                        ->hidden(true)
                        ->columnSpan('full')
                        ->label('Distance (KM)')
                        ->placeholder('Distance at dispatch')
                        ->numeric()
                        ->readOnly(true),

                ])->columns(4)->label('Route Snapshot')->hidden(true),
            
            ])->columns(2)->columnSpan('full'),
            ]);
    }
}
