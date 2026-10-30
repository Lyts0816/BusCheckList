<?php

namespace App\Filament\Resources\AssignedComputers\Schemas;

use App\Models\AssignedComputer;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class AssignedComputerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('assignment_type')
                    ->label('Assignment Type')
                    ->options([
                        'computer_set' => 'Computer Set',
                        'individual' => 'Individual Peripheral',
                    ])
                    ->default('computer_set')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        if ($state === 'individual') {
                            $set('system_unit_id', null);
                            $set('keyboard_id', null);
                            $set('mouse_id', null);
                            $set('monitor_id', null);
                            $set('ups_id', null);
                        } else {
                            $set('peripheral_id', null);
                        }
                    })
                    ->helperText('Choose Computer Set for a complete workstation or Individual Peripheral for any standalone peripheral.'),

                TextInput::make('assigned_to')
                    ->label('Assigned To')
                    ->placeholder('Person, team, or location (for example: Data Cabinet)')
                    ->disabled(fn (?AssignedComputer $record): bool => $record?->exists ?? false)
                    ->required(),

                TextInput::make('computer_name')
                    ->label('Computer / Location Name')
                    ->placeholder('Computer name or location, for example: user000 / Data Cabinet')
                    ->required(),

                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->preload()
                    ->searchable()
                    ->disabled(fn (?AssignedComputer $record): bool => $record?->exists ?? false)
                    ->required(),

                Select::make('peripheral_id')
                    ->label('Peripheral')
                    ->helperText('All peripheral types are available for individual assignments.')
                    ->relationship('peripheral', 'serial_number', fn ($query) => $query->orderBy('id', 'desc'))
                    ->getOptionLabelFromRecordUsing(fn ($record): string => self::assetLabel($record))
                    ->preload()
                    ->searchable()
                    ->searchPrompt('Search by type, serial number, asset code, or model...')
                    ->visible(fn (Get $get): bool => $get('assignment_type') === 'individual')
                    ->dehydrated(fn (Get $get): bool => $get('assignment_type') === 'individual')
                    ->required(fn (Get $get): bool => $get('assignment_type') === 'individual')
                    ->rules(fn (?AssignedComputer $record): array => [
                        self::assetUniquenessRule('peripheral_id', $record),
                    ]),

                Select::make('system_unit_id')
                    ->label('System Unit')
                    ->relationship('systemUnit', 'serial_number', fn ($query) => $query->orderBy('id', 'desc'))
                    ->getOptionLabelFromRecordUsing(fn ($record): string => self::assetLabel($record))
                    ->preload()
                    ->searchable()
                    ->searchPrompt('Search by serial number, asset code, or model...')
                    ->visible(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->dehydrated(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->nullable()
                    ->rules(fn (Get $get, ?AssignedComputer $record): array => $get('assignment_type') === 'computer_set'
                        ? [
                            'required_without_all:keyboard_id,mouse_id,monitor_id,ups_id',
                            self::assetUniquenessRule('system_unit_id', $record),
                        ]
                        : [])
                    ->validationMessages([
                        'required_without_all' => 'Select at least one asset for a computer set.',
                    ]),

                Select::make('keyboard_id')
                    ->label('Keyboard')
                    ->relationship('keyboard', 'serial_number', fn ($query) => $query->where('item_type', 'Keyboard')->orderBy('id', 'desc'))
                    ->getOptionLabelFromRecordUsing(fn ($record): string => self::assetLabel($record))
                    ->searchable()
                    ->preload()
                    ->placeholder('No Keyboard')
                    ->visible(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->dehydrated(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->nullable()
                    ->rules(fn (Get $get, ?AssignedComputer $record): array => $get('assignment_type') === 'computer_set'
                        ? [self::assetUniquenessRule('keyboard_id', $record)]
                        : []),

                Select::make('mouse_id')
                    ->label('Mouse')
                    ->relationship('mouse', 'serial_number', fn ($query) => $query->where('item_type', 'Mouse')->orderBy('id', 'desc'))
                    ->getOptionLabelFromRecordUsing(fn ($record): string => self::assetLabel($record))
                    ->searchable()
                    ->preload()
                    ->placeholder('No Mouse')
                    ->visible(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->dehydrated(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->nullable()
                    ->rules(fn (Get $get, ?AssignedComputer $record): array => $get('assignment_type') === 'computer_set'
                        ? [self::assetUniquenessRule('mouse_id', $record)]
                        : []),

                Select::make('monitor_id')
                    ->label('Monitor')
                    ->relationship('monitor', 'serial_number', fn ($query) => $query->where('item_type', 'Monitor')->orderBy('id', 'desc'))
                    ->getOptionLabelFromRecordUsing(fn ($record): string => self::assetLabel($record))
                    ->searchable()
                    ->preload()
                    ->placeholder('No Monitor')
                    ->visible(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->dehydrated(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->nullable()
                    ->rules(fn (Get $get, ?AssignedComputer $record): array => $get('assignment_type') === 'computer_set'
                        ? [self::assetUniquenessRule('monitor_id', $record)]
                        : []),

                Select::make('ups_id')
                    ->label('UPS')
                    ->relationship('ups', 'serial_number', fn ($query) => $query->where('item_type', 'UPS')->orderBy('id', 'desc'))
                    ->getOptionLabelFromRecordUsing(fn ($record): string => self::assetLabel($record))
                    ->searchable()
                    ->preload()
                    ->placeholder('No UPS')
                    ->visible(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->dehydrated(fn (Get $get): bool => $get('assignment_type') === 'computer_set')
                    ->nullable()
                    ->rules(fn (Get $get, ?AssignedComputer $record): array => $get('assignment_type') === 'computer_set'
                        ? [self::assetUniquenessRule('ups_id', $record)]
                        : []),
            ]);
    }

    private static function assetLabel(object $record): string
    {
        return implode(' - ', array_filter([
            $record->item_type,
            $record->serial_number,
            $record->asset_code,
            $record->model,
        ])) ?: "Asset #{$record->getKey()}";
    }

    private static function assetUniquenessRule(string $field, ?AssignedComputer $record): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($field, $record): void {
            if (blank($value)) {
                return;
            }

            $query = AssignedComputer::query()->where(function ($query) use ($field, $value): void {
                if ($field === 'system_unit_id') {
                    $query->where('system_unit_id', $value);

                    return;
                }

                $query->where('peripheral_id', $value)
                    ->orWhere('keyboard_id', $value)
                    ->orWhere('mouse_id', $value)
                    ->orWhere('monitor_id', $value)
                    ->orWhere('ups_id', $value);
            });

            if ($record?->exists) {
                $query->whereKeyNot($record->getKey());
            }

            if ($query->exists()) {
                $fail('This asset is already assigned and can only be assigned once.');
            }
        };
    }
}
