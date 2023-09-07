<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderDetails extends Model
{

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function ScopePopularProduct($query, $limit=20){
        return $query->selectRaw('sum(product_qty) max_order_product_qty, count(order_details.order_no) max_order_product, order_details.product_id, products.*')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->groupBy('order_details.product_id')
        ->orderByDesc('max_order_product_qty')
        ->limit($limit);
    }
    
}
