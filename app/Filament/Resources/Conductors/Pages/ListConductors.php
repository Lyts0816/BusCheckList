<?php

namespace App\Filament\Resources\Conductors\Pages;

use App\Filament\Resources\Conductors\ConductorsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ConductorsImport;
use Illuminate\Support\Facades\Storage;

class ListConductors extends ListRecords
{
    protected static string $resource = ConductorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Conductor')
                ->modalHeading('Create New Conductor'),

            Action::make('ImportConductors')
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
                        Excel::import(new ConductorsImport, $fullPath);

                        \Filament\Notifications\Notification::make()
                            ->title('Conductors imported successfully')
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
