<?php

namespace App\Filament\Resources\BorrowLogbooks;

use App\Filament\Resources\BorrowLogbooks\Pages\CreateBorrowLogbook;
use App\Filament\Resources\BorrowLogbooks\Pages\EditBorrowLogbook;
use App\Filament\Resources\BorrowLogbooks\Pages\ListBorrowLogbooks;
use App\Filament\Resources\BorrowLogbooks\Pages\ViewBorrowLogbook;
use App\Filament\Resources\BorrowLogbooks\Schemas\BorrowLogbookForm;
use App\Filament\Resources\BorrowLogbooks\Schemas\BorrowLogbookInfolist;
use App\Filament\Resources\BorrowLogbooks\Tables\BorrowLogbooksTable;
use App\Models\BorrowLogbook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;

class BorrowLogbookResource extends Resource
{
    protected static ?string $model = BorrowLogbook::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'Borrow logbook';

    protected static UnitEnum|string|null $navigationGroup = 'LOGBOOKS';

    protected static ?string $navigationLabel = 'Borrow Logbook';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return BorrowLogbookForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BorrowLogbookInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BorrowLogbooksTable::configure($table);
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
            'index' => ListBorrowLogbooks::route('/'),
            'create' => CreateBorrowLogbook::route('/create'),
            'view' => ViewBorrowLogbook::route('/{record}'),
            'edit' => EditBorrowLogbook::route('/{record}/edit'),
        ];
    }
}
