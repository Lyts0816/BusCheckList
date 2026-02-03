<?php

namespace App\Imports;

use App\Models\BusNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

class BusNumberImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation, WithBatchInserts
{
    private $importedBusNumbers = [];
    private $rowCount = 0;

    public function model(array $row)
    {
        $busNumber = strtoupper(trim((string)$row['bus_number'] ?? ''));
        
        return new BusNumber([
            'bus_number' => $busNumber,
            'bus_model' => strtoupper(trim($row['bus_model'] ?? '')),
            'bus_type' => strtoupper(trim($row['bus_type'] ?? '')),
            'seat_capacity' => $row['seat_capacity'] ?? null,
        ]);
    }
    
    public function batchSize(): int
    {
        return 100;
    }
    
    public function prepareForValidation($data, $index)
    {
        $busNumber = strtoupper(trim((string)($data['bus_number'] ?? '')));
        
        // Check for duplicates within the same import file
        if (in_array($busNumber, $this->importedBusNumbers)) {
            throw new \Exception("Duplicate bus number '{$busNumber}' found in the import file at row " . ($index + 2) . ".");
        }
        
        $this->importedBusNumbers[] = $busNumber;
        $this->rowCount++;
        
        return $data;
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
