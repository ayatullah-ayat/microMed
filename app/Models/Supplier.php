<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    protected $guarded = ['id'];

    protected $table = 'suppliers';

    public static function getSuppliers(){
        $supplierdata = DB::table('suppliers')
                        ->select('supplier_name','supplier_email','supplier_phone','supplier_address','current_balance')
                        ->get()
                        ->toArray();
        return $supplierdata;
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}
