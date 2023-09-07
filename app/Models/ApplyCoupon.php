<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Model;

class ApplyCoupon extends Model
{
    // public $timestamps  = false;

    protected $guarded = ['id'];

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
