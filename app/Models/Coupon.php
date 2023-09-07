<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\ApplyCoupon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = ['id'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'product_id');
    }

    public function applycoupons(){
        return $this->hasMany(ApplyCoupon::class, 'coupon_id');
    }


}
