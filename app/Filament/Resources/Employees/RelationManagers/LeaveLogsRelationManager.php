<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use App\Models\LeaveLog;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Size;
use Filament\Tables;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;

class LeaveLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'leaveLogs';

    protected static ?string $title = 'Leave Records';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()
                ->dense()
                ->schema([
                    Section::make()
                        ->dense()
                        ->gap(false)
                        ->schema([
                            Forms\Components\TextInput::make('control_number')
                                ->columnSpan(2)
                                ->label('Control Number')
                                ->default(fn(): string => LeaveLog::generateNextControlNumber())
                                ->readOnly()
                                ->dehydrated()
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),

                            Forms\Components\DatePicker::make('date_filed')
                                ->columnSpan(2)
                                ->label('Date Filed')
                                ->default(now())
                                ->required(),

                            Forms\Components\Select::make('leave_type')
                                ->columnSpan(2)
                                ->options([
                                    'Sick Leave' => 'Sick Leave',
                                    'Vacation Leave' => 'Vacation Leave',
                                    'Emergency Leave' => 'Emergency Leave',
                                    'Maternity Leave' => 'Maternity Leave',
                                    'Paternity Leave' => 'Paternity Leave',
                                    'Other' => 'Other',
                                ])
                                ->required(),

                            Forms\Components\TextInput::make('company')
                                ->columnSpan(2)
                                ->default('Yellow Bus Line Inc.')
                                ->required()
                                ->maxLength(255),
                        ])->columnSpan(2),

                    Section::make()
                        ->dense()
                        ->gap(false)
                        ->schema([
                            Forms\Components\TextInput::make('relieved_by')
                                ->columnSpanFull()
                                ->required()
                                ->maxLength(255),

                            Forms\Components\DatePicker::make('from_date')
                                ->columnSpanFull()
                                ->required(),

                            Forms\Components\DatePicker::make('to_date')
                                ->columnSpanFull()
                                ->required(),
                        ])->columnSpan(3),

                    Section::make()
                        ->dense()
                        ->gap(false)
                        ->schema([
                            Forms\Components\TextInput::make('conformed_by')
                                ->columnSpanFull()
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('conformed_by_position')
                                ->columnSpanFull()
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('approved_by')
                                ->columnSpanFull()
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('approved_by_position')
                                ->columnSpanFull()
                                ->maxLength(255),
                        ])->columnSpan(3),

                    Forms\Components\Textarea::make('reason')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('remarks')
                        ->columnSpanFull(),
                ])
                ->columns(8)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('control_number')
                    ->label('Control Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_filed')
                    ->label('Date Filed')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('from_date')
                    ->label('From')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('to_date')
                    ->label('To')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company')
                    ->label('Company'),

                Tables\Columns\TextColumn::make('leave_type')
                    ->label('Leave Type')
                    ->badge(),

                Tables\Columns\TextColumn::make('relieved_by')
                    ->label('Relieved By'),

                Tables\Columns\TextColumn::make('conformed_by')
                    ->label('Conformed By'),

                Tables\Columns\TextColumn::make('conformed_by_position')
                    ->label('Conformed By Position'),

                Tables\Columns\TextColumn::make('approved_by')
                    ->label('Approved By'),

                Tables\Columns\TextColumn::make('approved_by_position')
                    ->label('Approved By Position'),
            ])
            ->headerActions([
                CreateAction::make()
                ->modalWidth('7xl'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth('7xl'),

                    EditAction::make()
                        ->modalWidth('7xl'),

                    Action::make('print')
                        ->label('Print')
                        ->color('info')
                        ->icon('heroicon-o-printer')
                        ->url(fn ($record) => route('export.leave-logs.print', ['id' => $record->id]))
                        ->openUrlInNewTab(),
                ])->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->dropdownPlacement('bottom-start')
                    ->color('primary'),
            ], position: RecordActionsPosition::BeforeCells)

            ->filters([
                SelectFilter::make('leave_type')
                    ->label('Leave Type')
                    ->options(fn (): array => LeaveLog::query()
                        ->whereNotNull('leave_type')
                        ->distinct()
                        ->orderBy('leave_type')
                        ->pluck('leave_type', 'leave_type')
                        ->toArray()
                    )
                    ->searchable()
                    ->multiple(),
            ])
            ->deferFilters(false)

            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('success')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            $exportUrl = route('export.leave-logs') . '?ids=' . implode(',', $ids);
                            return redirect($exportUrl);
                        }),

                    DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->hasSuperAdminLeaveModule() ?? false),
                ]),
            ]);
    }
}
