<?php

namespace App\Models\Custom;
use Illuminate\Database\Eloquent\Model;
use App\Models\Custom\CustomServiceCategory;

class OurCustomService extends Model
{
    protected $guarded = ['id'];

    public function servicecategory(){
        return $this->hasMany(CustomServiceCategory::class);
    }


}
