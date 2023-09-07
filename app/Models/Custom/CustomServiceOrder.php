<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Model;
// use App\Models\Custom\CustomServiceCustomer;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomServiceOrder extends Model
{
    protected $guarded = ['id'];

    protected $table = 'custom_service_orders';

    public static function getCustomServiceOrder(){
        $serviceorderdata =  DB::table('custom_service_orders')->select('created_at','order_no','customer_name','customer_phone','custom_service_product_name','order_attachment')->get()->toArray();

        return $serviceorderdata;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(CustomServiceProduct::class,'custom_service_product_id');
    }

}
