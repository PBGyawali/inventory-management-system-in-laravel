<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    use HasFactory;


    protected $fillable = ['purchase_id','tax','price','product_id','quantity'];

    // public $timestamps = false;


    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'purchase_id','purchase_id');
    }
}
