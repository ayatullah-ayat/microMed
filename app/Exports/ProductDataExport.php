<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductDataExport implements FromCollection, WithHeadings, WithEvents, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings():array 
    {
        return [
            'Image',
            'Product Name',
            'Category',
            'Unit',
            'Purchase Price',
            'Sales Price',
            'Total Qty',
            'Total Stock Qty',
            'Total Stock Out Qty'
        ];
    }

    public function collection()
    {
        return collect(Product::getExcelData());
    }


    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:I1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('DD4B39');

                $workSheet = $event->sheet->getDelegate();
                foreach ($this->getDrawings() as $drawing) {
                    $drawing->setWorksheet($workSheet);
                }


            },

        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['argb' => 'FFFFFF'],
                ]
            ],
        ];
    }


    public function getDrawings(): array
    {
        $rowNum     = 2;
        $drawings   = [];

        foreach ($this->collection() as $row) {
            $imagePath = public_path('/'). $row['product_thumbnail_image'];
            $drawing = new Drawing();
            $drawing->setPath($imagePath);
            $drawing->setHeight(50);
            $drawing->setWidth(90);
            // $drawing->setOffsetX(5);
            // $drawing->setOffsetY(5);
            $drawing->setCoordinates('A' . $rowNum);
            $drawings[] = $drawing;

            $rowNum++;
        }

        return $drawings;
    }
}
