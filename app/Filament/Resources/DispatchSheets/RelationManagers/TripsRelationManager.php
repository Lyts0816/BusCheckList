<?php

namespace App\Filament\Resources\DispatchSheets\RelationManagers;

use App\Filament\Resources\DispatchedTrips\Schemas\DispatchedTripsForm;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

class TripsRelationManager extends RelationManager
{
    protected static string $relationship = 'trips';

    protected static ?string $title = 'Dispatch Trips';

    public function form(Schema $schema): Schema
    {
        return DispatchedTripsForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip_number')
                    ->label('Trip Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date_time_of_departure')
                    ->dateTime('M d, Y h:i A')
                    ->label('Departure')
                    ->sortable(),

                TextColumn::make('date_time_of_arrival')
                    ->dateTime('M d, Y h:i A')
                    ->label('Arrival')
                    ->sortable(),

                TextColumn::make('route.from')
                    ->label('Route')
                    ->formatStateUsing(fn($record) => $record->route?->from . ' - ' . $record->route?->to)
                    ->sortable(),

                TextColumn::make('busNumber.bus_number')
                    ->label('Bus Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('driver.driver_name')
                    ->label('Driver')
                    ->sortable(),
            ])
            ->defaultSort('trip_number', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->modalWidth('7xl')
                    ->mutateDataUsing(function (array $data): array {
                        $data['total_travel_time_minutes'] =
                            (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);

                        $data['total_add_time_minutes'] =
                            (($data['add_time_hours'] ?? 0) * 60) + ($data['add_time_minutes'] ?? 0);

                        unset($data['hours'], $data['minutes'], $data['add_time_hours'], $data['add_time_minutes']);

                        return $data;
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('7xl'),
                EditAction::make()
                    ->modalWidth('7xl')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $totalTravelMinutes = $data['total_travel_time_minutes'] ?? 0;
                        $data['hours'] = intdiv($totalTravelMinutes, 60);
                        $data['minutes'] = $totalTravelMinutes % 60;

                        $totalAddMinutes = $data['total_add_time_minutes'] ?? 0;
                        $data['add_time_hours'] = intdiv($totalAddMinutes, 60);
                        $data['add_time_minutes'] = $totalAddMinutes % 60;

                        return $data;
                    })
                    ->mutateDataUsing(function (array $data): array {
                        $data['total_travel_time_minutes'] =
                            (($data['hours'] ?? 0) * 60) + ($data['minutes'] ?? 0);

                        $data['total_add_time_minutes'] =
                            (($data['add_time_hours'] ?? 0) * 60) + ($data['add_time_minutes'] ?? 0);

                        unset($data['hours'], $data['minutes'], $data['add_time_hours'], $data['add_time_minutes']);

                        return $data;
                    }),
                DeleteAction::make(),
            ]);
    }
}
