<?php

namespace App\Exports;

use App\Models\SaleReturn;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SaleReturnExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Invoice NO',
            'Product Name',
            'Returned Date',
            'Returned Qty',
            'Returned Total'
        ];
    }

    public function collection()
    {
        return collect(SaleReturn::getReturnSaleData());
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => [
                    'bold' => true,
                    'size' => 11
                ]],
        ];
    }

}
