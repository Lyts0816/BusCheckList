<?php

namespace App\Imports;

use App\Models\Drivers;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class DriversImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    private $importedNames = [];

    public function model(array $row)
    {
        $driverName = strtoupper(trim($row['driver_name'] ?? $row['name'] ?? ''));
        
        // Track imported names to check for duplicates within the same file
        $this->importedNames[] = $driverName;
        
        return new Drivers([
            'driver_name' => $driverName,
            'status' => strtoupper(trim($row['status'] ?? 'ACTIVE')),
            'remarks' => strtoupper(trim($row['remarks'] ?? '')),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.driver_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'driver_name'),
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.driver_name.unique' => 'The driver name :input already exists in the database.',
            '*.driver_name.required' => 'Driver name is required.',
        ];
    }
}
