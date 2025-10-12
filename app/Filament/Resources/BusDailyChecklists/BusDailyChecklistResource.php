<?php

namespace App\Filament\Resources\BusDailyChecklists;

use App\Filament\Resources\BusDailyChecklists\Pages\CreateBusDailyChecklist;
use App\Filament\Resources\BusDailyChecklists\Pages\EditBusDailyChecklist;
use App\Filament\Resources\BusDailyChecklists\Pages\ListBusDailyChecklists;
use App\Filament\Resources\BusDailyChecklists\Pages\ViewBusDailyChecklist;
use App\Filament\Resources\BusDailyChecklists\Schemas\BusDailyChecklistForm;
use App\Filament\Resources\BusDailyChecklists\Schemas\BusDailyChecklistInfolist;
use App\Filament\Resources\BusDailyChecklists\Tables\BusDailyChecklistsTable;
use App\Models\BusDailyChecklist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

use UnitEnum;

class BusDailyChecklistResource extends Resource
{
    protected static ?string $model = BusDailyChecklist::class;

    protected static string|BackedEnum|null $navigationIcon = 'ionicon-bus-outline';

    protected static ?string $recordTitleAttribute = 'Bus Daily Checklist';

        protected static UnitEnum|string|null $navigationGroup = 'Bus Checklists';

    public static function form(Schema $schema): Schema
    {
        return BusDailyChecklistForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusDailyChecklistInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusDailyChecklistsTable::configure($table);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            
        ];
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
            'index' => ListBusDailyChecklists::route('/'),
            'create' => CreateBusDailyChecklist::route('/create'),
            'view' => ViewBusDailyChecklist::route('/{record}'),
            'edit' => EditBusDailyChecklist::route('/{record}/edit'),
        ];
    }
}
