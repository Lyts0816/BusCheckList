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
                    ->label('Trip Number')
                    ->columnSpanFull()
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

                Section::make('Trip Details')
                    ->schema([
                        Select::make('route_id')
                            ->label('From-To Route')
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
                            ->label('KM Run')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(9999)
                            ->default(0)
                            ->suffix('km')
                            ->columnSpan(1),

                        Select::make('bus_number_id')
                            ->label('Bus Number')
                            ->required()
                            ->options(BusNumber::pluck('bus_number', 'id'))
                            ->createOptionForm([
                                TextInput::make('bus_number')
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return BusNumber::create([
                                    'bus_number' => $data['bus_number'],
                                ])->getKey();
                            })
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('bus_class_id')
                            ->label('Bus Class')
                            ->required()
                            ->options(BusClass::pluck('class_name', 'id'))
                            ->createOptionForm([
                                TextInput::make('class_name')
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return BusClass::create([
                                    'class_name' => $data['class_name'],
                                ])->getKey();
                            })
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('nature_of_trip_id')
                            ->label('Nature of Trip')
                            ->required()
                            ->options(NatureOfTrip::pluck('nature_of_trip_name', 'id'))
                            ->createOptionForm([
                                TextInput::make('nature_of_trip_name')
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return NatureOfTrip::create([
                                    'nature_of_trip_name' => $data['nature_of_trip_name'],
                                ])->getKey();
                            })
                            ->searchable()
                            ->columnSpan(2),

                        Select::make('driver_id')
                            ->label('Driver')
                            ->required()
                            ->options(Drivers::pluck('driver_name', 'id'))
                            ->createOptionForm([
                                TextInput::make('driver_name')
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return Drivers::create([
                                    'driver_name' => $data['driver_name'],
                                ])->getKey();
                            })
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('conductor_id')
                            ->label('Conductor')
                            ->required()
                            ->options(Conductors::pluck('conductor_name', 'id'))
                            ->createOptionForm([
                                TextInput::make('conductor_name')
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return Conductors::create([
                                    'conductor_name' => $data['conductor_name'],
                                ])->getKey();
                            })
                            ->searchable()
                            ->columnSpan(1),

                    ])->columns(2)->columnSpanFull(),

                Section::make('DateTime Information')
                    ->schema([
                        DateTimePicker::make('date_time_in_terminal')
                            ->label('Date/Time in Terminal')
                            ->required()
                            ->seconds(false)
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_parking')
                            ->label('Date/Time of Parking')
                            ->required()
                            ->seconds(false)
                            ->afterOrEqual('date_time_in_terminal')
                            ->validationMessages([
                                'after_or_equal' => 'Parking time must be after or equal to terminal time.',
                            ])
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_departure')
                            ->label('Date/Time of Departure')
                            ->required()
                            ->seconds(false)
                            ->afterOrEqual('date_time_of_parking')
                            ->validationMessages([
                                'after_or_equal' => 'Departure time must be after or equal to parking time.',
                            ])
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_arrival')
                            ->label('Date/Time of Arrival')
                            ->required()
                            ->seconds(false)
                            ->afterOrEqual('date_time_of_departure')
                            ->validationMessages([
                                'after_or_equal' => 'Arrival time must be after or equal to departure time.',
                            ])
                            ->columnSpan(1),

                        TimePicker::make('idle_time_start')
                            ->label('Idle Time Start')
                            ->seconds(false)
                            ->columnSpan(1),

                        TimePicker::make('idle_time_end')
                            ->label('Idle Time End')
                            ->seconds(false)
                            ->afterOrEqual('idle_time_start')
                            ->validationMessages([
                                'after_or_equal' => 'Idle time end must be after or equal to idle time start.',
                            ])
                            ->columnSpan(1),
                    ])->columns(2)->columnSpanFull(),

                Section::make('Trip Statistics')
                    ->schema([
                        Section::make('Total Travel Time')
                            ->schema([
                                TextInput::make('hours')
                                    ->label('Hours')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999)
                                    ->default(0)
                                    ->suffix('hrs')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('minutes')
                                    ->label('Minutes')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(59)
                                    ->default(0)
                                    ->suffix('mins')
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->columnSpan(1),

                        Section::make('Total Additional Time')
                            ->schema([
                                TextInput::make('add_time_hours')
                                    ->label('Hours')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999)
                                    ->default(0)
                                    ->suffix('hrs')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('add_time_minutes')
                                    ->label('Minutes')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(59)
                                    ->default(0)
                                    ->suffix('mins')
                                    ->required()
                                    ->columnSpan(1),
                            ])->columnSpan(1),

                        // TextInput::make('ticket_number')
                        //     ->label('Ticket Number')
                        //     ->integer()
                        //     ->default(0)
                        //     ->columnSpan(1),

                        // TextInput::make('passengers_on_board')
                        //     ->label('Passengers on Board')
                        //     ->integer()
                        //     ->default(0)
                        //     ->columnSpan(1),

                        // TextInput::make('baggage_amount')
                        //     ->label('Baggage Amount')
                        //     ->integer()
                        //     ->default(0)
                        //     ->columnSpan(1),

                        // TextInput::make('baggage_ticket_no')
                        //     ->label('Baggage Ticket No')
                        //     ->integer()
                        //     ->default(0)
                        //     ->columnSpan(1),
                    ])->columns(2)->columnSpanFull(),

                Textarea::make('remarks')
                    ->label('Remarks')
                    ->nullable()
                    ->maxLength(500)
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
