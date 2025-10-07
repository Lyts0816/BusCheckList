<?php

namespace App\Filament\Resources\AssignedComputers;

use App\Filament\Resources\AssignedComputers\Pages\CreateAssignedComputer;
use App\Filament\Resources\AssignedComputers\Pages\EditAssignedComputer;
use App\Filament\Resources\AssignedComputers\Pages\ListAssignedComputers;
use App\Filament\Resources\AssignedComputers\Pages\ViewAssignedComputer;
use App\Filament\Resources\AssignedComputers\Schemas\AssignedComputerForm;
use App\Filament\Resources\AssignedComputers\Schemas\AssignedComputerInfolist;
use App\Filament\Resources\AssignedComputers\Tables\AssignedComputersTable;
use App\Models\AssignedComputer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssignedComputerResource extends Resource
{
    protected static ?string $model = AssignedComputer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Assign Computer';

    public static function form(Schema $schema): Schema
    {
        return AssignedComputerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssignedComputerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignedComputersTable::configure($table);
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
            'index' => ListAssignedComputers::route('/'),
            'create' => CreateAssignedComputer::route('/create'),
            'view' => ViewAssignedComputer::route('/{record}'),
            'edit' => EditAssignedComputer::route('/{record}/edit'),
        ];
    }
}
