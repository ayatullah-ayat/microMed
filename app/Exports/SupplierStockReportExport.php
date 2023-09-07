<?php

namespace App\Exports;

use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierStockReportExport implements FromCollection, WithHeadings, WithMapping,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data = null;

    protected $summaryRow = [
        'Total Summary=',
        '',
        '',
        '',
        0,
        0,
        0,
        0,
        0,
    ];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data ? $this->data : [];
    }

    public function prepareRows($rows)
    {
        foreach($rows as $row){
            $this->summaryRow[4]+= $row->sales_price;
            $this->summaryRow[5]+= $row->product_price;
            $this->summaryRow[6]+= $row->product_qty;
            $this->summaryRow[7]+= $row->stocked_qty;
            $this->summaryRow[8]+= $row->returned_qty;
        }

        $rows[]=$this->summaryRow;

       return $rows;

    }

    public function map($data): array
    {
        if(is_array($data)){
            return ($data);
        }

        return [
            $data->supplier_name,
            $data->product_name,
            $data->category_name,
            $data->product_unit,
            $data->sales_price,
            $data->product_price,
            $data->product_qty,
            $data->stocked_qty,
            $data->returned_qty,
        ];
    }

    public function headings(): array {
        return [
            'Supplier Name', 
            'Product Name', 
            'Category Name',
            'Unit',
            'Sales Price',
            'Purchase Price',
            'In Qty',
            'Stock Qty',
            'Return Qty'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $footerCount = count($this->data) ? count($this->data) + 2 : 2;
        return [
            // Style the first row as bold text.
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['argb' => 'FFFFFF'],
                ]
            ],
            $footerCount => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['argb' => 'FFFFFF'],
                ]
            ],
        ];
    }


    public function registerEvents(): array
    {

        // dd(count($this->data));
        return [

            AfterSheet::class => function (AfterSheet $event) {

                // dd('fsdfd');
                $footerCount = count($this->data) ? count($this->data) + 2 : 2;
                $header = $event->sheet->getDelegate()->getStyle('A1:I1');
                $header->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DD4B39');
                $footer = $event->sheet->getDelegate()->getStyle("A{$footerCount}:I{$footerCount}");
                $footer->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DD4B39');
            },

        ];
    }


    
}
