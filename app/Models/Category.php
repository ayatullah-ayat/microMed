<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // protected $table   = "categories";
    protected $guarded = ['id'];

    public function subCategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
