<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseReturn extends Model
{
    protected $guarded = ['id'];

    protected $table = 'purchase_returns';

    public static function getPurchaseReturn(){
        $purchasereturn = DB::table('purchase_returns')
                        ->select('invoice_no','product_name','created_at','returned_qty','subtotal')
                        ->get()
                        ->toArray();

        return $purchasereturn;
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
