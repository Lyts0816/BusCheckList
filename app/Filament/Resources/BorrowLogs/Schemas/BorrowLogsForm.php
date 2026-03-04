<?php

namespace App\Filament\Resources\BorrowLogs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BorrowLogsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                DatePicker::make('borrowed_date')
                    ->label('Date Borrowed')
                    ->required(),

                TextInput::make('borrower_name')
                    ->label('Employee Name')
                    ->required(),

                Select::make('department')
                    ->label('Department')
                    ->options([
                        'HR' => 'HR',
                        'MIS' => 'MIS',
                        'Production' => 'Production',
                        'Accounting' => 'Accounting',
                        'Cash' => 'Cash',
                        'Operation' => 'Operation',
                        'Clinic' => 'Clinic',
                        'Terminal' => 'Terminal',
                    ])
                    ->required(),

                TextInput::make('department_head_name')
                    ->label('Department Head Name')
                    ->required(),

                TextInput::make('purpose_borrowing')
                    ->label('Purpose of Borrowing')
                    ->required(),

                TextInput::make('handled_by')
                    ->label('Handled By')
                    ->required(),

                /* 👇 THIS IS THE IMPORTANT PART */
                Repeater::make('items')
                    ->label('Borrowed Items')
                    ->relationship() // uses Borrow::items()
                    ->schema([
                        TextInput::make('item_name')
                            ->label('Item / Equipment')
                            ->required(),

                        TextInput::make('item_asset_code')
                            ->label('Item Asset Code')
                            ->helperText('Enter serial number if asset code is not available')
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->required(),

                        DatePicker::make('return_date')
                            ->label('Date Returned')
                            ->afterOrEqual('borrow_date'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Borrowed' => 'Borrowed',
                                'Returned' => 'Returned',
                                // 'Damaged' => 'Damaged',
                                // 'Lost' => 'Lost',
                            ])
                            ->default('Borrowed')
                            ->columnSpanFull(),
                    ])
                    ->minItems(1)
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}
