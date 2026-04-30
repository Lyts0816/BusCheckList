<?php

namespace App\Filament\Resources\TurnOvers;

use App\Filament\Resources\TurnOvers\Pages\CreateTurnOver;
use App\Filament\Resources\TurnOvers\Pages\EditTurnOver;
use App\Filament\Resources\TurnOvers\Pages\ListTurnOvers;
use App\Filament\Resources\TurnOvers\Pages\ViewTurnOver;
use App\Filament\Resources\TurnOvers\Schemas\TurnOverForm;
use App\Filament\Resources\TurnOvers\Schemas\TurnOverInfolist;
use App\Filament\Resources\TurnOvers\Tables\TurnOversTable;
use App\Models\TurnOver;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TurnOverResource extends Resource
{
    protected static ?string $model = TurnOver::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $recordTitleAttribute = 'Turn Over Items';

    protected static UnitEnum|string|null $navigationGroup = 'Turn Over Items';


    public static function form(Schema $schema): Schema
    {
        return TurnOverForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TurnOverInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TurnOversTable::configure($table);
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
            'index' => ListTurnOvers::route('/'),
            'create' => CreateTurnOver::route('/create'),
            'view' => ViewTurnOver::route('/{record}'),
            'edit' => EditTurnOver::route('/{record}/edit'),
        ];
    }
}
