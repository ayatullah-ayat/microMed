<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SaleDataExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Invoice NO',
            'Customer Name',
            'Date',
            'Total Qty',
            'Grand Total',
            'Total Payment',
            'Total Payment Due',
            'Sales Note'
        ];
    }

    public function collection()
    {
        return collect(Sale::getSalesdata());
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => [
                    'bold' => true,
                    'size' => 11
                ]],
        ];
    }
    

}
