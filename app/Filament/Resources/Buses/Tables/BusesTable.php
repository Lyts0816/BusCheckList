<?php

namespace App\Filament\Resources\Buses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable()
                    ->label('ID')
                    ->hidden(),

                TextColumn::make('bus_number')
                    ->label('Bus Number')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('model')
                    ->toggleable()
                    ->label('Model')
                    ->searchable(),

                TextColumn::make('status')
                    ->toggleable()
                    ->label('Status')
                    ->searchable(),

                TextColumn::make('base_location')
                    ->toggleable()
                    ->label('Base Location')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', direction: 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
