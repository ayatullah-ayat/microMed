<?php

namespace App\Exports;

use App\Models\OtherOrder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OtherOrdertExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Order Date',
            'Order NO',
            'Category Name',
            'Quantity',
            'Price',
            'Total Price',
            'Total Bill',
            'Advaced Amount',
            'Due Amount',
            'Mobile',
            'Company',
            'Address',
            'Note'
        ];
    }

    public function collection()
    {
        return collect(OtherOrder::getOtherOrderData());
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ]
            ],
        ];
    }

}
