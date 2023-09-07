<?php

namespace App\Exports;

use App\Models\Custom\CustomServiceOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomServiceOrdertExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Order Date',
            'Order NO',
            'Customer Name',
            'Customer Phone',
            'Product Name',
            'Order Attachment'
        ];
    }

    public function collection()
    {
        return collect(CustomServiceOrder::getCustomServiceOrder());
    }


}
