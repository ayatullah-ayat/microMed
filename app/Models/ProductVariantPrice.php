<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $table= "product_variant_prices";
    protected $guarded = ['id'];

}
