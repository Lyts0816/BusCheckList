<?php

namespace App\Filament\Resources\Drivers\Pages;

use App\Filament\Resources\Drivers\DriversResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DriversImport;
use Illuminate\Support\Facades\Storage;

class ListDrivers extends ListRecords
{
    protected static string $resource = DriversResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Driver')
                ->modalHeading('Create New Driver'),

            Action::make('ImportDrivers')
                ->label('Upload Excel')
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
                        Excel::import(new DriversImport, $fullPath);

                        \Filament\Notifications\Notification::make()
                            ->title('Drivers imported successfully')
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
