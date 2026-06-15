<?php

namespace App\Filament\Resources\Shipments\Tables;

use App\Models\Shipment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ShipmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tracking_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('or_number')
                    ->label('OR #')
                    ->searchable(),

                TextColumn::make('sender_name')
                    ->searchable(),

                TextColumn::make('recipient_name')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'gray',
                        'in_transit' => 'info',
                        'arrived' => 'warning',
                        'claimed' => 'success',
                        'cancelled' => 'danger',
                    }),

                TextColumn::make('claimed_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('arrived_at')
                    ->dateTime()
                    ->sortable(),

            ])->defaultSort('id', direction: 'desc')

            ->filters([
                //
            ])

            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
                    ->icon('heroicon-m-eye')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('info'),

                ActionGroup::make([
                    // Barcode scan simulation / manual trigger
                    Action::make('markArrived')
                        ->label('Mark Arrived')
                        ->color('warning')
                        ->action(fn (Shipment $record) => $record->update([
                            'status' => 'arrived',
                            'arrived_at' => now(),
                        ])),

                    Action::make('markClaimed')
                        ->label('Mark Claimed')
                        ->color('success')
                        ->action(fn (Shipment $record) => $record->update([
                            'status' => 'claimed',
                            'claimed_at' => now(),
                        ])),

                    Action::make('print')
                        ->label('Print Label')
                        ->icon('heroicon-o-printer')
                        ->url(fn (Shipment $record) => route('shipments.print', $record))
                        ->openUrlInNewTab(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('primary'),

            ], position: RecordActionsPosition::BeforeCells)

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
