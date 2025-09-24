<?php

namespace App\Filament\Resources\ItemsChecklists;

use App\Filament\Resources\ItemsChecklists\Pages\CreateItemsChecklist;
use App\Filament\Resources\ItemsChecklists\Pages\EditItemsChecklist;
use App\Filament\Resources\ItemsChecklists\Pages\ListItemsChecklists;
use App\Filament\Resources\ItemsChecklists\Pages\ViewItemsChecklist;
use App\Filament\Resources\ItemsChecklists\Schemas\ItemsChecklistForm;
use App\Filament\Resources\ItemsChecklists\Schemas\ItemsChecklistInfolist;
use App\Filament\Resources\ItemsChecklists\Tables\ItemsChecklistsTable;
use App\Models\ItemsChecklist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ItemsChecklistResource extends Resource
{
    protected static ?string $model = ItemsChecklist::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'Items Checklist & Monitoring';

    public static function form(Schema $schema): Schema
    {
        return ItemsChecklistForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemsChecklistInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemsChecklistsTable::configure($table);
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
            'index' => ListItemsChecklists::route('/'),
            'create' => CreateItemsChecklist::route('/create'),
            'view' => ViewItemsChecklist::route('/{record}'),
            'edit' => EditItemsChecklist::route('/{record}/edit'),
        ];
    }
}
