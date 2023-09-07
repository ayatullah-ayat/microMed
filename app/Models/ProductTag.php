<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];


    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

}
