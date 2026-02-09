<?php

namespace App\Filament\Resources\DispatchedTrips\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimeInput;
use Filament\Forms\Components\TimeInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use App\Models\Routes;
use App\Models\BusNumber;
use Filament\Schemas\Components\Section;
use App\Models\BusClass;
use App\Models\NatureOfTrip;
use App\Models\Drivers;
use App\Models\Conductors;
use App\Models\DispatchedTrips;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Grid;
use Closure;


class DispatchedTripsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trip_number')
                    ->inlineLabel()
                    ->label('Trip #')
                    ->columnSpanFull()
                    ->extraInputAttributes(['class' => 'h-5 text-2xs p-0.5'])
                    ->required()
                    ->maxLength(100)
                    ->default(function () {
                        $lastTrip = DispatchedTrips::latest('id')->first();
                        if (!$lastTrip) {
                            return 'TRP000001';
                        }
                        $lastNumber = (int) substr($lastTrip->trip_number, 3);
                        return 'TRP' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
                    })
                    ->disabled()
                    ->dehydrated(),

                Section::make()
                    ->schema([
                        Select::make('route_id')
                            ->inlineLabel()
                            ->label('Route')
                            ->placeholder('Route')
                            ->required()
                            ->options(Routes::all()->mapWithKeys(fn($route) => [$route->id => $route->from . ' - ' . $route->to]))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $distance = Routes::find($state)?->distance;
                                $set('km_run', $distance);
                            })
                            ->columnSpan(1),

                        TextInput::make('km_run')
                            ->inlineLabel()
                            ->label('KM')
                            ->placeholder('KM')
                            ->extraInputAttributes(['class' => 'h-5 w-12 text-2xs p-0.5'])
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(9999)
                            ->default(0)
                            ->columnSpan(1),

                        Select::make('bus_number_id')
                            ->inlineLabel()
                            ->label('Bus #')
                            ->placeholder('Bus')
                            ->required()
                            ->options(BusNumber::pluck('bus_number', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('bus_class_id')
                            ->inlineLabel()
                            ->label('Class')
                            ->placeholder('Class')
                            ->required()
                            ->options(BusClass::pluck('class_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('nature_of_trip_id')
                            ->inlineLabel()
                            ->label('Nature')
                            ->placeholder('Nature')
                            ->required()
                            ->options(NatureOfTrip::pluck('nature_of_trip_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('driver_id')
                            ->inlineLabel()
                            ->label('Driver')
                            ->placeholder('Driver')
                            ->required()
                            ->options(Drivers::pluck('driver_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('conductor_id')
                            ->inlineLabel()
                            ->label('Conductor')
                            ->placeholder('Conductor')
                            ->required()
                            ->options(Conductors::pluck('conductor_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                    ])->columns(7)->columnSpanFull(),

                Section::make()
                    ->schema([
                        DateTimePicker::make('date_time_in_terminal')
                            ->inlineLabel()
                            ->label('In Terminal')
                            ->placeholder('In Terminal')
                            ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5'])
                            ->required()
                            ->seconds(false)
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_parking')
                            ->inlineLabel()
                            ->label('Parking')
                            ->placeholder('Parking')
                            ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5'])
                            ->required()
                            ->seconds(false)
                            ->afterOrEqual('date_time_in_terminal')
                            ->validationMessages([
                                'after_or_equal' => 'Parking time must be after or equal to terminal time.',
                            ])
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_departure')
                            ->inlineLabel()
                            ->label('Departure')
                            ->placeholder('Departure')
                            ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5'])
                            ->required()
                            ->seconds(false)
                            ->afterOrEqual('date_time_of_parking')
                            ->validationMessages([
                                'after_or_equal' => 'Departure time must be after or equal to parking time.',
                            ])
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_arrival')
                            ->inlineLabel()
                            ->label('Arrival')
                            ->placeholder('Arrival')
                            ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5'])
                            ->required()
                            ->seconds(false)
                            ->afterOrEqual('date_time_of_departure')
                            ->validationMessages([
                                'after_or_equal' => 'Arrival time must be after or equal to departure time.',
                            ])
                            ->columnSpan(1),

                        TimePicker::make('idle_time_start')
                            ->inlineLabel()
                            ->extraInputAttributes(['class' => 'h-5 w-14 text-2xs p-0.5'])
                            ->label('Idle Start')
                            ->seconds(false)
                            ->columnSpan(1),

                        TimePicker::make('idle_time_end')
                            ->inlineLabel()
                            ->label('Idle End')
                            ->extraInputAttributes(['class' => 'h-5 w-14 text-2xs p-0.5'])
                            ->maxWidth('xs')
                            ->seconds(false)
                            ->afterOrEqual('idle_time_start')
                            ->validationMessages([
                                'after_or_equal' => 'Idle time end must be after or equal to idle time start.',
                            ])
                            ->columnSpan(1),
                    ])->columns(6)->columnSpanFull(),

                Section::make()
                    ->schema([
                        TextInput::make('hours')
                            ->inlineLabel()
                            ->label('Hours')
                            ->extraInputAttributes(['class' => 'h-5 w-12 text-2xs p-0.5'])
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999)
                            ->default(0)
                            ->suffix('h')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('minutes')
                            ->inlineLabel()
                            ->label('Mins')
                            ->extraInputAttributes(['class' => 'h-5 w-12 text-2xs p-0.5'])
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(59)
                            ->default(0)
                            ->suffix('m')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('add_time_hours')
                            ->inlineLabel()
                            ->label('Add H')
                            ->extraInputAttributes(['class' => 'h-5 w-12 text-2xs p-0.5'])
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999)
                            ->default(0)
                            ->suffix('h')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('add_time_minutes')
                            ->inlineLabel()
                            ->label('Add M')
                            ->extraInputAttributes(['class' => 'h-5 w-12 text-2xs p-0.5'])
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(59)
                            ->default(0)
                            ->suffix('m')
                            ->required()
                            ->columnSpan(1),

                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->nullable()
                            ->maxLength(500)
                            ->rows(1)
                            ->columnSpan(2),
                    ])->columns(6)->columnSpanFull(),
            ]);
    }
}
