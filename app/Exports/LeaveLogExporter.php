<?php

namespace App\Exports;

use App\Models\LeaveLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Database\Eloquent\Builder;

class LeaveLogExporter implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected ?array $ids = null;

    public function setIds(array $ids): self
    {
        $this->ids = $ids;
        return $this;
    }

    public function query(): Builder
    {
        $query = LeaveLog::with('employee');

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Control Number',
            'Date Filed',
            'Employee Name',
            'Department',
            'Leave Type',
            'Company',
            'From Date',
            'To Date',
            'Reason',
            'Relieved By',
            'Conformed By',
            'Conformed By Position',
            'Approved By',
            'Approved By Position',
            'Remarks',
        ];
    }

    public function map($row): array
    {
        return [
            $row->control_number,
            $row->date_filed?->format('Y-m-d'),
            $row->employee?->full_name ?? 'N/A',
            $row->employee?->department ?? 'N/A',
            $row->leave_type,
            $row->company,
            $row->from_date?->format('Y-m-d'),
            $row->to_date?->format('Y-m-d'),
            $row->reason,
            $row->relieved_by,
            $row->conformed_by,
            $row->conformed_by_position,
            $row->approved_by,
            $row->approved_by_position,
            $row->remarks ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '366092'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->setAutoFilter('A1:O1');

        // Set column widths
        $widths = ['A' => 15, 'B' => 12, 'C' => 20, 'D' => 15, 'E' => 15, 'F' => 20, 'G' => 12, 'H' => 12, 'I' => 20, 'J' => 15, 'K' => 15, 'L' => 18, 'M' => 15, 'N' => 18, 'O' => 15];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Apply borders to all cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:O{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }
}
