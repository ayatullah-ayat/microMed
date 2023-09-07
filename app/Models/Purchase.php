<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Purchase extends Model
{

    protected $guarded = ['id'];

    protected $table = 'purchases';

    public static function getPurchasedata(){
        $purchasedata = DB::table('purchases')
                        ->select('invoice_no','supplier_name','purchase_date','total_qty','total_price','total_payment','total_payment_due','purchase_note')
                        ->get()
                        ->toArray();

        return $purchasedata;
    }
    
    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
}
