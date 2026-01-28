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
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('bus_class_id')
                            ->label('Bus Class')
                            ->required()
                            ->options(BusClass::pluck('class_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('nature_of_trip_id')
                            ->label('Nature of Trip')
                            ->required()
                            ->options(NatureOfTrip::pluck('nature_of_trip_name', 'id'))
                            ->searchable()
                            ->columnSpan(2),

                        Select::make('driver_id')
                            ->label('Driver')
                            ->required()
                            ->options(Drivers::pluck('driver_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('conductor_id')
                            ->label('Conductor')
                            ->required()
                            ->options(Conductors::pluck('conductor_name', 'id'))
                            ->searchable()
                            ->columnSpan(1),
                    ])->columns(2)->columnSpanFull(),

                Section::make('DateTime Information')
                    ->schema([
                        DateTimePicker::make('date_time_in_terminal')
                            ->label('Date/Time in Terminal')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_parking')
                            ->label('Date/Time of Parking')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->afterOrEqual('date_time_in_terminal')
                            ->validationMessages([
                                'after_or_equal' => 'Parking time must be after or equal to terminal time.',
                            ])
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_departure')
                            ->label('Date/Time of Departure')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->afterOrEqual('date_time_of_parking')
                            ->validationMessages([
                                'after_or_equal' => 'Departure time must be after or equal to parking time.',
                            ])
                            ->columnSpan(1),

                        DateTimePicker::make('date_time_of_arrival')
                            ->label('Date/Time of Arrival')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->afterOrEqual('date_time_of_departure')
                            ->validationMessages([
                                'after_or_equal' => 'Arrival time must be after or equal to departure time.',
                            ])
                            ->columnSpan(1),

                        TimePicker::make('idle_time_start')
                            ->label('Idle Time Start')
                            ->native(false)
                            ->seconds(false)
                            ->columnSpan(1),

                        TimePicker::make('idle_time_end')
                            ->label('Idle Time End')
                            ->native(false)
                            ->seconds(false)
                            ->afterOrEqual('idle_time_start')
                            ->validationMessages([
                                'after_or_equal' => 'Idle time end must be after or equal to idle time start.',
                            ])
                            ->columnSpan(1),
                    ])->columns(2)->columnSpanFull(),

                Section::make('Trip Statistics')
                    ->schema([
                        TextInput::make('total_travel_time_minutes')
                            ->label('Total Travel Time (Hours)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99999)
                            ->default(0)
                            ->suffix('Hrs')
                            ->columnSpan(1),

                        TextInput::make('total_add_time_minutes')
                            ->label('Total Add Time (Minutes)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99999)
                            ->default(0)
                            ->suffix('mins')
                            ->columnSpan(1),

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
