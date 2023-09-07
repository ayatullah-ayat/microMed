<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    protected $guarded = ['id'];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function scopeTotalSaleProductPurchase($query){
        return $query->selectRaw('round(sum(sale_products.purchase_price * sale_products.product_qty),2) as result');
    }
}
