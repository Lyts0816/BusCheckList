<?php

namespace App\Filament\Resources\SupplyTransactions\Schemas;

use App\Models\OfficeSupplies;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;

class SupplyTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            Select::make('type')
                ->options([
                    'IN' => 'IN',
                    'OUT' => 'OUT',
                    'ADJUSTMENT' => 'ADJUSTMENT',
                ])
                ->required(),

            TextInput::make('user')
                ->label('Recipient Name')
                ->required(),

            Textarea::make('remarks')
                ->columnSpanFull(),

            Repeater::make('items')
                ->dense()
                ->relationship('items') 
                ->schema([
                    Select::make('supply_id')
                        ->label('Supply')
                        ->preload()
                        ->searchable()
                        ->relationship('supply', 'name') 
                        ->required(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->required()
                        ->rule(function ($get) {
                            return function (string $attribute, $value, Closure $fail) use ($get): void {
                                $type = $get('../../type');
                                $supplyId = $get('supply_id');

                                if ($type !== 'OUT' || $supplyId === null || $value === null) {
                                    return;
                                }

                                $stock = OfficeSupplies::whereKey($supplyId)->value('stock');

                                if ($stock === null) {
                                    return;
                                }

                                if ((float) $value > (float) $stock) {
                                    $fail("Only {$stock} left in stock.");
                                }
                            };
                        }),
                ])
                ->collapsible()->columnSpanFull()->columns(2),
            ]);
    }
}
