<?php

namespace App\Filament\Resources\AssignmentTransferHistories;

use App\Filament\Resources\AssignmentTransferHistories\Pages\ListAssignmentTransferHistories;
use App\Filament\Resources\AssignmentTransferHistories\Tables\AssignmentTransferHistoriesTable;
use App\Models\AssignmentTransferHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AssignmentTransferHistoryResource extends Resource
{
    protected static ?string $model = AssignmentTransferHistory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Assignment Transfer History';

    protected static UnitEnum|string|null $navigationGroup = 'COMPUTER & PERIPHERALS';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return AssignmentTransferHistoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssignmentTransferHistories::route('/'),
        ];
    }
}
