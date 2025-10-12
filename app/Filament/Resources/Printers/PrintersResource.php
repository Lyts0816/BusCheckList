<?php

namespace App\Filament\Resources\Printers;

use App\Filament\Resources\Printers\Pages\CreatePrinters;
use App\Filament\Resources\Printers\Pages\EditPrinters;
use App\Filament\Resources\Printers\Pages\ListPrinters;
use App\Filament\Resources\Printers\Pages\ViewPrinters;
use App\Filament\Resources\Printers\Schemas\PrintersForm;
use App\Filament\Resources\Printers\Schemas\PrintersInfolist;
use App\Filament\Resources\Printers\Tables\PrintersTable;
use App\Models\Printer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

use UnitEnum;

class PrintersResource extends Resource
{
    protected static ?string $model = Printer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $recordTitleAttribute = 'Printers';

    protected static UnitEnum|string|null $navigationGroup = 'Computer Inventory';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PrintersForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PrintersInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrintersTable::configure($table);
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
            'index' => ListPrinters::route('/'),
            'create' => CreatePrinters::route('/create'),
            'view' => ViewPrinters::route('/{record}'),
            'edit' => EditPrinters::route('/{record}/edit'),
        ];
    }
}
