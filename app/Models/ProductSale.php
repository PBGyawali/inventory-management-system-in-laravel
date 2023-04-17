<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;
    protected $primaryKey='product_sales_id';

	protected $fillable = ['sale_id','tax','price','product_id','quantity'];

    public $timestamps = false;

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }
}
