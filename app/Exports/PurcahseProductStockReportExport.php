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

class PurcahseProductStockReportExport implements FromCollection, WithHeadings, WithMapping,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data = null;

    protected $summaryRow = [
        'Total Summary=',
        '',
        '',
        0,
        0,
        0,
        0,
        0,
        0,
        0
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
            $this->summaryRow[4]+= $row->product_qty;
            $this->summaryRow[5]+= $row->stocked_qty;
            $this->summaryRow[6]+= $row->returned_qty;
            $this->summaryRow[7]+= $row->in_amount;
            $this->summaryRow[8]+= $row->in_stock_amount;
            $this->summaryRow[9]+= $row->in_return_amount;
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
            $data->product_unit,
            $data->product_price,
            $data->product_qty,
            $data->stocked_qty,
            $data->returned_qty,
            $data->in_amount,
            $data->in_stock_amount,
            $data->in_return_amount
        ];
    }


    public function headings(): array {
        return [
            'Supplier Name', 
            'Product Name', 
            'Unit', 
            'Supplier Price', 
            'In Qty', 
            'Stock Qty',
            'Returned Qty',
            'In Amount',
            'Stock Amount',
            'Returned Amount'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $footerCount = count($this->data) ? count($this->data) + 2 : 2;
        return [
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
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $footerCount = count($this->data) ? count($this->data) + 2 : 2;
                $header = $event->sheet->getDelegate()->getStyle('A1:J1');
                $header->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DD4B39');
                $footer = $event->sheet->getDelegate()->getStyle("A{$footerCount}:J{$footerCount}");
                $footer->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DD4B39');
            },

        ];
    }


}
