<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderDataExport implements FromCollection, WithHeadings, WithEvents, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings():array 
    {
        return [
            'Order Date',
            'Order No',
            'Sizes',
            'Colors',
            'Total Qty',
            'Total Price',
            'Discount Price',
            'Total Payment',
            'Payment Type',
            'Customer Name',
            'Customer Phone',
            'Shipping Address',
            'Order Note',
            'Status',
        ];
    }

    public function collection()
    {
        return collect(Order::getOrderData());
    }


    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:N1')

                ->getFill()
                // ->applyFromArray(['fillType' => 'solid', 'rotation' => 0,'font' => [
                //     'bold' => true,
                // ], 'color' => ['rgb' => 'D9D9D9'],] );
                // ->getFont()
                // ->setBold(true);

                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)

                ->getStartColor()

                ->setARGB('DD4B39');
            },

        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['argb' => 'FFFFFF'],
                ]],

            // // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    
}
