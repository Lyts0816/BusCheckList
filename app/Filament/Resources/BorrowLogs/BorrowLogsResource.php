<?php

namespace App\Filament\Resources\BorrowLogs;

use App\Filament\Resources\BorrowLogs\Pages\CreateBorrowLogs;
use App\Filament\Resources\BorrowLogs\Pages\EditBorrowLogs;
use App\Filament\Resources\BorrowLogs\Pages\ListBorrowLogs;
use App\Filament\Resources\BorrowLogs\Pages\ViewBorrowLogs;
use App\Filament\Resources\BorrowLogs\Schemas\BorrowLogsForm;
use App\Filament\Resources\BorrowLogs\Schemas\BorrowLogsInfolist;
use App\Filament\Resources\BorrowLogs\Tables\BorrowLogsTable;
use App\Models\BorrowLogs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


use UnitEnum;

class BorrowLogsResource extends Resource
{
    protected static ?string $model = BorrowLogs::class;


    protected static ?string $recordTitleAttribute = 'Borrow Logs';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';


    protected static UnitEnum|string|null $navigationGroup = 'LOGBOOKS';

    public static function form(Schema $schema): Schema
    {
        return BorrowLogsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BorrowLogsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BorrowLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'items',
                'items as not_returned_count' => function ($query) {
                    $query->whereNull('status')->orWhere('status', '!=', 'returned');
                },
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBorrowLogs::route('/'),
            // 'create' => CreateBorrowLogs::route('/create'),
            // 'view' => ViewBorrowLogs::route('/{record}'),
            // 'edit' => EditBorrowLogs::route('/{record}/edit'),
        ];
    }
}
