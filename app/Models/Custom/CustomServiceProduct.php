<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Model;
use App\Models\Custom\CustomServiceOrder;

class CustomServiceProduct extends Model
{
    protected $guarded = ['id'];

    public function orders()
    {
        return $this->hasMany(CustomServiceOrder::class);
    }

    public function service()
    {
        return $this->belongsTo(OurCustomService::class, 'service_id');
    }

    public function category()
    {
        return $this->belongsTo(CustomServiceCategory::class, 'category_id');
    }

}
