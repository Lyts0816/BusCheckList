<?php

namespace App\Filament\Resources\BorrowLogbooks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BorrowLogbookForm
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
                    ])
                    ->required(),
                TextInput::make('equipment')
                    ->label('Item/Equipment')
                    ->required(),
                TextInput::make('item_asset_code')
                    ->helperText('Enter serial number if asset code is not available')
                    ->label('Item Asset Code')
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
                DatePicker::make('date_returned')
                    ->label('Date Returned'),
                TextInput::make('remarks')
                    ->label('Remarks'),
            ]);
    }
}
