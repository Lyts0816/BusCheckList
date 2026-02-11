<?php

namespace App\Filament\Resources\DispatchedTrips\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use App\Models\BusNumber;
use App\Models\DispatchSheet;
use Filament\Schemas\Components\FusedGroup;
use App\Models\NatureOfTrip;
use App\Models\DispatchedTrips;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;

class DispatchedTripsForm
{
    public static function configure(Schema $schema): Schema
    {
        $setBusDetails = function (?int $busId, callable $set): void {
            if (! $busId) {
                $set('bus_class', null);
                $set('driver_name', null);
                $set('conductor_name', null);

                return;
            }

            $bus = BusNumber::with(['driver', 'conductor'])->find($busId);
            $set('bus_class', $bus?->bus_class);
            $set('driver_name', $bus?->driver?->driver_name);
            $set('conductor_name', $bus?->conductor?->conductor_name);
        };

        return $schema
            ->components([

                Grid::make()
                    ->gap(false)
                    ->dense()
                    ->schema([

                        //--------------------------------------------
                        // Trip Details Section
                        //--------------------------------------------
                        Section::make()
                            ->dense()
                            ->gap(false)
                            ->schema([
                                TextInput::make('trip_number')
                                    ->inlineLabel()
                                    ->label('Trip #')
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

                                Select::make('dispatch_sheet_id')
                                    ->label('Dispatch Sheet')
                                    ->placeholder('Dispatch Sheet')
                                    ->required(fn($livewire) => ! ($livewire instanceof RelationManager))
                                    ->relationship('dispatchSheet', 'id')
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $routeLabel = $record->route ? ($record->route->from . ' - ' . $record->route->to) : 'No Route';
                                        return $routeLabel . ' | ' . $record->dispatch_date;
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) use ($setBusDetails) {
                                        $sheet = DispatchSheet::find($state);
                                        $set('bus_number_id', $sheet?->bus_number_id);
                                        $setBusDetails($sheet?->bus_number_id, $set);
                                    })
                                    ->hidden(fn($livewire) => $livewire instanceof RelationManager)
                                    ->columnSpan(1),

                                Select::make('bus_number_id')
                                    ->placeholder('select bus number')
                                    ->required()
                                    ->options(BusNumber::pluck('bus_number', 'id'))
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, callable $set) => $setBusDetails($state, $set))
                                    ->afterStateHydrated(fn($state, callable $set) => $setBusDetails($state, $set))
                                    ->columnSpan(1),

                                TextInput::make('bus_class')
                                    ->placeholder('Bus Class')
                                    ->readOnly(true)
                                    ->dehydrated(false)
                                    ->columnSpan(1),

                                Select::make('nature_of_trip_id')
                                    ->placeholder('Nature')
                                    ->required()
                                    ->options(NatureOfTrip::pluck('nature_of_trip_name', 'id'))
                                    ->searchable()
                                    ->columnSpan(1),

                                TextInput::make('driver_name')
                                    ->placeholder('Driver')
                                    ->readOnly(true)
                                    ->dehydrated(false)
                                    ->columnSpan(1),

                                TextInput::make('conductor_name')
                                    ->placeholder('Conductor')
                                    ->readOnly(true)
                                    ->dehydrated(false)
                                    ->columnSpan(1),
                            ])->columnSpan(2),

                        //--------------------------------------------
                        // Time Details Section
                        //--------------------------------------------
                        Section::make()

                            ->dense()
                            ->gap(false)
                            ->schema([
                                TimePicker::make('time_in_terminal')
                                    ->label('Time in Terminal')
                                    ->seconds(false)
                                    ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5', '@change' => '$el.blur()'])
                                    ->required()
                                    ->seconds(false)
                                    ->columnSpan(1),

                                TimePicker::make('time_of_parking')
                                    ->seconds(false)
                                    ->label('Time of Parking')
                                    ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5', '@change' => '$el.blur()'])
                                    ->required()
                                    ->seconds(false)
                                    ->afterOrEqual('time_in_terminal')
                                    ->validationMessages([
                                        'after_or_equal' => 'Parking time must be after or equal to terminal time.',
                                    ])
                                    ->columnSpan(1),

                                TimePicker::make('time_of_departure')
                                    ->seconds(false)
                                    ->label('Time of Departure')
                                    ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5', '@change' => '$el.blur()'])
                                    ->required()
                                    ->seconds(false)
                                    ->afterOrEqual('time_of_parking')
                                    ->validationMessages([
                                        'after_or_equal' => 'Departure time must be after or equal to parking time.',
                                    ])
                                    ->columnSpan(1),

                                TimePicker::make('time_of_arrival')
                                    ->seconds(false)
                                    ->label('Time of Arrival')
                                    ->extraInputAttributes(['class' => 'h-5 w-28 text-2xs p-0.5', '@change' => '$el.blur()'])
                                    ->required()
                                    ->seconds(false)
                                    ->afterOrEqual('time_of_departure')
                                    ->validationMessages([
                                        'after_or_equal' => 'Arrival time must be after or equal to departure time.',
                                    ])
                                    ->columnSpan(1),

                                TimePicker::make('idle_time_start')
                                    ->seconds(false)
                                    ->extraInputAttributes(['class' => 'h-5 w-14 text-2xs p-0.5', '@change' => '$el.blur()'])
                                    ->label('Time Idle Start')
                                    ->columnSpan(1),

                                TimePicker::make('idle_time_end')
                                    ->seconds(false)
                                    ->label('Time Idle End')
                                    ->extraInputAttributes(['class' => 'h-5 w-14 text-2xs p-0.5', '@change' => '$el.blur()'])
                                    ->seconds(false)
                                    ->afterOrEqual('idle_time_start')
                                    ->validationMessages([
                                        'after_or_equal' => 'Idle time end must be after or equal to idle time start.',
                                    ])
                                    ->columnSpan(1),
                            ]),

                        //--------------------------------------------
                        // Totals & Notes Section
                        //--------------------------------------------
                        Section::make()
                            ->dense()
                            ->gap(false)
                            ->schema([
                                TextInput::make('hours')
                                    ->label('Total Travel Time Hours')
                                    ->suffix('h')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999)
                                    ->default(0)
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('minutes')
                                    ->label('Total Travel Time Minutes')
                                    ->numeric()
                                    ->suffix('m')
                                    ->minValue(0)
                                    ->maxValue(59)
                                    ->default(0)
                                    ->columnSpan(1)
                                    ->required(),

                                TextInput::make('add_time_hours')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999)
                                    ->default(0)
                                    ->suffix('h')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('add_time_minutes')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(59)
                                    ->default(0)
                                    ->suffix('m')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('ticket_number')
                                    ->label('Ticket Serial #')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999999)
                                    ->default(0)
                                    ->columnSpan(1),

                                TextInput::make('passengers_on_board')
                                    ->label('Passengers on Board')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999)
                                    ->default(0)
                                    ->columnSpan(1),

                                TextInput::make('baggage_amount')
                                    ->label('Baggage Amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999999)
                                    ->default(0)
                                    ->columnSpan(1),

                                TextInput::make('baggage_ticket_no')
                                    ->label('Baggage Ticket #')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999999)
                                    ->default(0)
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999999)
                                    ->default(0)
                                    ->columnSpan(1),

                                Textarea::make('remarks')
                                    ->placeholder('Remarks')
                                    ->nullable()
                                    ->maxLength(500)
                                    ->rows(1)
                                    ->columnSpan(2),
                            ])->columns(2)->columnSpan(2)

                    ])->columns(5)->columnSpanFull(),



            ]);
    }
}
