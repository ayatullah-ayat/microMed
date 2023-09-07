<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OtherOrder extends Model
{
    protected $guarded = ['id'];

    protected $table = 'other_orders';
    
    public static function getOtherOrderData(){
        $otherdata = DB::table('other_orders')
        ->select('order_date','order_no','category_name','order_qty','price','total_order_price',DB::raw('round((total_order_price + service_charge)-order_discount_price,0) as total_bill'),'advance_balance','due_price','moible_no','institute_description','address','note')
        ->orderByDesc('order_date')
        ->get()->toArray();
        return $otherdata;
    }

    public function categories() {
        return $this->hasMany(OtherOrder::class, 'order_no', 'order_no');
    }
}
