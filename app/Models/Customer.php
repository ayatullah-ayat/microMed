<?php

namespace App\Models;

use App\Models\Custom\CustomServiceOrder;
use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\CustomerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    protected $guarded = ['id'];
    protected $table = 'customers';

    public static function getCustomers(){
        $customerdata = DB::table('customers')
                        ->select('customer_name','customer_email','customer_phone','customer_address','current_balance')
                        ->get()
                        ->toArray();
        return $customerdata;
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function customerTypes()
    {
        return $this->hasMany(CustomerType::class, 'customer_type');
    }

    public function customerType($type='customize')
    {
        return $this->hasOne(CustomerType::class, 'customer_id')->where('customer_type', $type);
    }
    
}
  