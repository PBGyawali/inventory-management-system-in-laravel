<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Product extends Model
{
    use HasFactory;
    use PowerJoins;

    protected $primaryKey='product_id';

    protected $casts = ['created_at' => 'datetime'];

    public $timestamps = false;
    protected $hidden = ['password','remember_token'];

    protected $fillable = ['product_name','product_status','brand_id',
    'category_id','product_description','product_date','user_id',
    'product_base_price','product_tax','product_unit','product_quantity',
    'opening_stock','defective_quantity','status'];

    public function getProductNameAttribute($name){
        return ucwords($name);
    }

    public function getProductDescriptionAttribute($name){
        return ucwords($name);
    }
    public function setProductNameAttribute($name){
        $this->attributes['product_name'] = ucwords($name);
    }

    public function product_sale()
    {
        return $this->hasMany(ProductSale::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id' );
    }

    protected static function booted()
    {
        static::creating(function ($Post) {
            $Post->user_id = auth()->user()->id;
            $Post->product_date = date('Y-m-d');
        });
    }
}
