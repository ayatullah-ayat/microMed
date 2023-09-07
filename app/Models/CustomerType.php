<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{

    protected $guarded = ['id'];

    
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
