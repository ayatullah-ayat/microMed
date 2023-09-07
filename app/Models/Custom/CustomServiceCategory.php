<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Model;
use App\Models\Custom\CustomServiceProduct;
use App\Models\Custom\OurCustomService;


class CustomServiceCategory extends Model
{
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(CustomServiceProduct::class);
    }

    public function customservice() {
        return $this->belongsTo(OurCustomService::class,'service_id');
    }

}
