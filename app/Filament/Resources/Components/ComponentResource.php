<?php

namespace App\Filament\Resources\Components;

use App\Filament\Resources\Components\Pages\ListComponents;
use App\Filament\Resources\Components\Schemas\ComponentForm;
use App\Filament\Resources\Components\Schemas\ComponentInfolist;
use App\Filament\Resources\Components\Tables\ComponentsTable;
use App\Models\Component;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $recordTitleAttribute = 'Assign Computer';

    protected static UnitEnum|string|null $navigationGroup = 'MAINTENANCE LOGS';

    public static function form(Schema $schema): Schema
    {
        return ComponentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ComponentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComponentsTable::configure($table);
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
            'index' => ListComponents::route('/'),
            // 'create' => CreateComponent::route('/create'),
            // 'view' => ViewComponent::route('/{record}'),
            // 'edit' => EditComponent::route('/{record}/edit'),
        ];
    }
}
