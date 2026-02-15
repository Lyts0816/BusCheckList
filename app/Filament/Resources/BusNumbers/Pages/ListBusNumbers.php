<?php

namespace App\Filament\Resources\BusNumbers\Pages;

use App\Filament\Resources\BusNumbers\BusNumberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BusNumberImport;
use App\Models\BusNumber;
use Illuminate\Support\Facades\Storage;

class ListBusNumbers extends ListRecords
{
    protected static string $resource = BusNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Bus Number')
                ->modalHeading('Create New Bus Number'),

            Action::make('ImportBusNumbers')
                ->label('Upload Excel')
                ->authorize(fn (): bool => Filament::auth()->user()?->can('import', BusNumber::class) ?? false)
                ->visible(fn (): bool => Filament::auth()->user()?->can('import', BusNumber::class) ?? false)
                ->modalSubmitActionLabel('Import')
                ->schema([
                    FileUpload::make('file')
                        ->required()
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv',
                        ])
                        ->maxSize(5120),
                ])
                ->action(function (array $data) {
                    $filePath = $data['file'] ?? null;

                    if (!$filePath) {
                        \Filament\Notifications\Notification::make()
                            ->title('No file selected')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Handle array of files (get first file)
                    if (is_array($filePath)) {
                        $filePath = $filePath[0] ?? null;
                    }

                    if (!$filePath) {
                        \Filament\Notifications\Notification::make()
                            ->title('No file selected')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        $fullPath = Storage::disk('local')->path($filePath);
                        Excel::import(new BusNumberImport, $fullPath);

                        \Filament\Notifications\Notification::make()
                            ->title('Bus numbers imported successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Import failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })

        ];
    }
}
