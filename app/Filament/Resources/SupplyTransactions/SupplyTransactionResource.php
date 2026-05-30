<?php

namespace App\Filament\Resources\SupplyTransactions;

use App\Filament\Resources\SupplyTransactions\Pages\CreateSupplyTransaction;
use App\Filament\Resources\SupplyTransactions\Pages\EditSupplyTransaction;
use App\Filament\Resources\SupplyTransactions\Pages\ListSupplyTransactions;
use App\Filament\Resources\SupplyTransactions\Pages\ViewSupplyTransaction;
use App\Filament\Resources\SupplyTransactions\Schemas\SupplyTransactionForm;
use App\Filament\Resources\SupplyTransactions\Schemas\SupplyTransactionInfolist;
use App\Filament\Resources\SupplyTransactions\Tables\SupplyTransactionsTable;
use App\Models\SupplyTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SupplyTransactionResource extends Resource
{
    protected static ?string $model = SupplyTransaction::class;

    protected static UnitEnum|string|null $navigationGroup = 'SUPPLIES INVENTORY';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'Supply Transactions';

    protected static ?string $navigationLabel = 'Supply Transactions';

    public static function form(Schema $schema): Schema
    {
        return SupplyTransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplyTransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplyTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplyTransactions::route('/'),
            // 'create' => CreateSupplyTransaction::route('/create'),
            // 'view' => ViewSupplyTransaction::route('/{record}'),
            // 'edit' => EditSupplyTransaction::route('/{record}/edit'),
        ];
    }
}
