<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    use Exportable;
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $userlist =  Category::all();
        // dd($userlist);

        return new Collection([
            ['ID','Name','Email','Phone','Address','Company']
        ]);

        // return Order::all();
    }
    
    // public function orderlist() :array
    // {
    //     return [
    //         'ID',
    //         'Name',
    //         'Email',
    //     ];
    // }


}
