<?php

namespace App\Imports;

use App\Models\Conductors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ConductorsImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    private $importedNames = [];

    public function model(array $row)
    {
        $conductorName = strtoupper(trim($row['conductor_name'] ?? $row['name'] ?? ''));
        
        // Track imported names to check for duplicates within the same file
        $this->importedNames[] = $conductorName;
        
        return new Conductors([
            'conductor_name' => $conductorName,
            'status' => strtoupper(trim($row['status'] ?? 'ACTIVE')),
            'remarks' => strtoupper(trim($row['remarks'] ?? '')),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.conductor_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('conductors', 'conductor_name'),
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.conductor_name.unique' => 'The conductor name :input already exists in the database.',
            '*.conductor_name.required' => 'Conductor name is required.',
        ];
    }
}
