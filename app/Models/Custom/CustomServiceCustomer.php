<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Model;
use App\Models\Custom\CustomServiceOrder;

class CustomServiceCustomer extends Model
{
    protected $guarded = ['id'];

    public function orders()
    {
        return $this->hasMany(CustomServiceOrder::class);
    }
}
