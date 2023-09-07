<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Product Name',
            'Category Name',
            'Product Unit',
            'Sales Price',
            'Purchase Price',
            'Total Product Qty',
            'Total Stock Out Qty',
            'Total Stock Qty'
        ];
    }

    public function collection()
    {
        return collect(Product::getProductReport());
    }

}
