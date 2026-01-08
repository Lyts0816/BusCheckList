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

                DatePicker::make('borrow_date')
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
                        TextInput::make('equipment')
                            ->label('Item / Equipment')
                            ->required(),

                        TextInput::make('item_asset_code')
                            ->label('Item Asset Code')
                            ->helperText('Enter serial number if asset code is not available')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'borrowed' => 'Borrowed',
                                'returned' => 'Returned',
                            ])
                            ->required(),

                        DatePicker::make('date_returned')
                            ->label('Date Returned')
                            ->afterOrEqual('borrow_date'),

                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->columnSpanFull(),
                    ])
                    ->minItems(1)
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}
