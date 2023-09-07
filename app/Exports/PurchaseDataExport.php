<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseDataExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Invoice NO',
            'Supplier Name',
            'Purchase Date',
            'Total Qty',
            'Total Price',
            'Total Payment',
            'Total Payment Due',
            'Purchase Note'
        ];
    }

    public function collection()
    {
        return collect(Purchase::getPurchasedata());
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
