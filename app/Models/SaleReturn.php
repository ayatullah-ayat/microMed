<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaleReturn extends Model
{
    protected $guarded = ['id'];

    protected $table = 'sale_returns';

    public static function getReturnSaleData(){
        $returnsaledata = DB::table('sale_returns')
                        ->select('invoice_no','product_name','created_at','returned_qty','subtotal')
                        ->get()
                        ->toArray();

        return $returnsaledata; 
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
