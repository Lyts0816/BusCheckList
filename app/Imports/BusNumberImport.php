<?php

namespace App\Imports;

use App\Models\BusNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class BusNumberImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    private $importedBusNumbers = [];

    public function model(array $row)
    {
        $busNumber = strtoupper(trim((string)$row['bus_number'] ?? ''));
        
        // Track imported bus numbers to check for duplicates within the same file
        $this->importedBusNumbers[] = $busNumber;
        
        return new BusNumber([
            'bus_number' => $busNumber,
            'bus_model' => strtoupper(trim($row['bus_model'] ?? '')),
            'bus_type' => strtoupper(trim($row['bus_type'] ?? '')),
            'seat_capacity' => $row['seat_capacity'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.bus_number' => [
                'required',
                'regex:/^[A-Z0-9]+$/',
                'max:255',
                Rule::unique('bus_numbers', 'bus_number'),
            ],
            '*.seat_capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.bus_number.unique' => 'The bus number :input already exists in the database.',
            '*.bus_number.required' => 'Bus number is required.',
            '*.bus_number.regex' => 'The bus number must contain only letters and numbers.',
            '*.seat_capacity.integer' => 'Seat capacity must be a number.',
            '*.seat_capacity.min' => 'Seat capacity must be at least 1.',
        ];
    }
}
