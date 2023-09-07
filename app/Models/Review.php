<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $fillable = ['ratting','commented_by','body','is_approved'];

    public function product()
    {
        return $this->belongsTo(Product::class ,'commentable_id');
    }

    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
    
}
