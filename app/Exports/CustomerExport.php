<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Customer Address',
            'Customer Balance'
        ];
    }

    public function collection()
    {
        return collect(Customer::getCustomers());
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
