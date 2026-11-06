<?php

namespace App\Filament\Resources\Transfers\Schemas;

use App\Models\Peripherals;
use App\Models\Printer;
use App\Models\SystemUnit;
use App\Models\Transfer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TransferForm
{
    /**
     * Text input that shows UPPERCASE while typing and saves as UPPERCASE.
     */
    protected static function upperInput(string $name): TextInput
    {
        return TextInput::make($name)
            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
            ->dehydrateStateUsing(fn($state) => filled($state) ? Str::upper(trim($state)) : null);
    }

    /**
     * Previously used values, offered as suggestions (cached for 5 minutes).
     */
    protected static function recent(string $column, array $extra = []): array
    {
        $values = Cache::remember("transfer-recent-{$column}", 300, fn() => Transfer::query()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->limit(50)
            ->pluck($column)
            ->all());

        return array_values(array_unique(array_merge($extra, $values)));
    }

    /**
     * Search all three inventory tables. Returns options grouped by type.
     * Keys are prefixed ("unit:12") so IDs from different tables never clash.
     */
    protected static function searchInventory(string $search): array
    {
        $like = "%{$search}%";

        $units = SystemUnit::query()
            ->where(fn($q) => $q
                ->where('asset_code', 'like', $like)
                ->orWhere('serial_number', 'like', $like)
                ->orWhere('model', 'like', $like)
                ->orWhere('asset_type', 'like', $like))
            ->limit(10)->get()
            ->mapWithKeys(fn($m) => ["unit:{$m->id}" => trim("{$m->asset_code} - {$m->asset_type} {$m->model}")])
            ->all();

        $peripherals = Peripheralss::query()
            ->where(fn($q) => $q
                ->where('asset_code', 'like', $like)
                ->orWhere('serial_number', 'like', $like)
                ->orWhere('model', 'like', $like)
                ->orWhere('item_type', 'like', $like))
            ->limit(10)->get()
            ->mapWithKeys(fn($m) => ["peripheral:{$m->id}" => trim("{$m->asset_code} - {$m->item_type} {$m->model}")])
            ->all();

        $printers = Printer::query()
            ->where(fn($q) => $q
                ->where('asset_code', 'like', $like)
                ->orWhere('printer_serial_number', 'like', $like)
                ->orWhere('printer_model', 'like', $like)
                ->orWhere('printer_host', 'like', $like))
            ->limit(10)->get()
            ->mapWithKeys(fn($m) => ["printer:{$m->id}" => trim("{$m->asset_code} - Printer {$m->printer_model}")])
            ->all();

        // array_filter drops empty groups
        return array_filter([
            'System Units' => $units,
            'Peripherals' => $peripherals,
            'Printers' => $printers,
        ]);
    }

    /**
     * Turn a selected key like "unit:12" into the transfer item fields.
     */
    protected static function inventoryItem(?string $key): ?array
    {
        if (! $key || ! str_contains($key, ':')) {
            return null;
        }

        [$type, $id] = explode(':', $key, 2);

        return match ($type) {
            'unit' => ($m = SystemUnit::find($id)) ? [
                'item_name' => trim("{$m->asset_type} {$m->model}"),
                'asset_code' => $m->asset_code,
                'serial_number' => $m->serial_number,
            ] : null,

            'peripheral' => ($m = Peripheral::find($id)) ? [
                'item_name' => trim("{$m->item_type} {$m->model}"),
                'asset_code' => $m->asset_code,
                'serial_number' => $m->serial_number,
            ] : null,

            'printer' => ($m = Printer::find($id)) ? [
                'item_name' => trim("Printer {$m->printer_model}"),
                'asset_code' => $m->asset_code,
                'serial_number' => $m->printer_serial_number,
            ] : null,

            default => null,
        };
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                Section::make('Transfer details')
                    ->description('When is this happening, and where is it going?')
                    ->columns(4)
                    ->schema([
                        DateTimePicker::make('date')
                            ->label('Date & time')
                            ->default(now())
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('d/m/Y h:i A')
                            ->required()
                            ->columnSpan(2),

                        ToggleButtons::make('status')
                            ->label('Status')
                            ->options([
                                'Pending' => 'Pending',
                                'Transferred' => 'Transferred',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->colors([
                                'Pending' => 'warning',
                                'Transferred' => 'success',
                                'Cancelled' => 'danger',
                            ])
                            ->icons([
                                'Pending' => 'heroicon-m-clock',
                                'Transferred' => 'heroicon-m-check-circle',
                                'Cancelled' => 'heroicon-m-x-circle',
                            ])
                            ->inline()
                            ->default('Transferred')
                            ->required()
                            ->columnSpan(2),

                        self::upperInput('from')
                            ->label('From (sending department)')
                            ->default('MIS')
                            ->datalist(fn() => self::recent('from', ['MIS']))
                            ->maxLength(50)
                            ->required()
                            ->columnSpan(2),

                        self::upperInput('to')
                            ->label('To (receiving department)')
                            ->placeholder('Type or pick a department')
                            ->datalist(fn() => self::recent('to'))
                            ->autofocus()
                            ->maxLength(50)
                            ->required()
                            ->columnSpan(2),
                    ]),

                Section::make('People involved')
                    ->description('Who prepared, who released it at the gate, and who received it?')
                    ->columns(3)
                    ->schema([
                        self::upperInput('prepared_by')
                            ->label('Prepared by')
                            ->datalist(fn() => self::recent('prepared_by', [
                                'MACRHYS RHYANNE MONDEJAR',
                                'MARK TOMAS',
                            ]))
                            ->maxLength(30)
                            ->required(),

                        self::upperInput('guard_on_duty')
                            ->label('Guard on duty')
                            ->datalist(fn() => self::recent('guard_on_duty'))
                            ->helperText('Optional')
                            ->maxLength(30),

                        self::upperInput('received_by')
                            ->label('Received by')
                            ->datalist(fn() => self::recent('received_by'))
                            ->helperText('Optional, can be filled in later')
                            ->maxLength(30),
                    ]),

                Section::make('Items being transferred')
                    ->description('Search the inventory to auto-fill an item, or type it in manually.')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                Select::make('quick_fill')
                                    ->label('Quick fill from inventory')
                                    ->placeholder('Search by asset code, model, or serial number')
                                    ->helperText('Not in the inventory? Skip this and type the details below.')
                                    ->searchable()
                                    ->live()
                                    ->dehydrated(false)
                                    ->getSearchResultsUsing(fn(string $search): array => self::searchInventory($search))
                                    ->getOptionLabelUsing(function ($value) {
                                        $item = self::inventoryItem($value);

                                        return $item ? trim("{$item['asset_code']} - {$item['item_name']}") : null;
                                    })
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if (! $item = self::inventoryItem($state)) {
                                            return;
                                        }

                                        $set('item_name', $item['item_name']);
                                        $set('asset_code', $item['asset_code']);
                                        $set('serial_number', $item['serial_number']);
                                    })
                                    ->columnSpanFull(),

                                TextInput::make('item_name')
                                    ->label('Item name')
                                    ->placeholder('e.g. Laptop, Monitor, CCTV Camera')
                                    ->datalist([
                                        'Desktop Computer',
                                        'Laptop',
                                        'Monitor',
                                        'Keyboard',
                                        'Mouse',
                                        'Printer',
                                        'UPS',
                                        'Router',
                                        'Network Switch',
                                        'CCTV Camera',
                                        'External Hard Drive',
                                        'Projector',
                                    ])
                                    ->required(),

                                TextInput::make('asset_code')
                                    ->label('Asset code')
                                    ->helperText('Leave blank if the item has no asset code'),

                                TextInput::make('serial_number')
                                    ->label('Serial number'),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->itemLabel(fn(array $state): string => filled($state['item_name'] ?? null)
                                ? trim($state['item_name'] . ' ' . ($state['asset_code'] ?? ''))
                                : 'New item')
                            ->addActionLabel('Add another item')
                            ->cloneable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
