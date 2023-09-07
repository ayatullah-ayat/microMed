<?php

namespace App\Exports;

use App\Models\OfficeAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class officeAccountDataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array {
        return [
            'Date',
            'Account Type',
            'Description',
            'Cash In',
            'Cash Out',
            'Current Balance',
            'Note'
        ];
    }

    public function collection()
    {
        return collect(OfficeAccount::getOfficeAccountData());
    }
}
