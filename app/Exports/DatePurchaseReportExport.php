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

class DatePurchaseReportExport implements FromCollection, WithHeadings, WithMapping,WithStyles,WithEvents
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
            $this->summaryRow[3]+= $row->total_qty;
            $this->summaryRow[4]+= $row->total_price;
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
            $data->invoice_no,
            $data->supplier_name,
            $data->purchase_date,
            $data->total_qty,
            $data->total_price
        ];
    }


    public function headings(): array {
        return [
            'Invoice NO', 
            'Supplier Name', 
            'Date', 
            'Total Qty', 
            'Total Amount'
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
                $header = $event->sheet->getDelegate()->getStyle('A1:E1');
                $header->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DD4B39');
                $footer = $event->sheet->getDelegate()->getStyle("A{$footerCount}:E{$footerCount}");
                $footer->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DD4B39');
            },

        ];
    }


}
