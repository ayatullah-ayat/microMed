<?php

namespace App\Models;

use App\Models\SaleProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    protected $guarded = ['id'];

    protected $table = 'sales';

    public static function getSalesdata(){
        $saledata = DB::table('sales')
                    ->select('invoice_no','customer_name','sales_date','sold_total_qty','order_grand_total','total_payment','total_payment_due','sales_note')
                    ->get()
                    ->toArray();

        return $saledata;
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


}
