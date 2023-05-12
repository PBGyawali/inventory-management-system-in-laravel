<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey='product_id';

    protected $casts = ['created_at' => 'datetime'];


    protected $hidden = ['password','remember_token'];

    protected $fillable = ['product_name','product_status','brand_id',
    'category_id','product_description','product_date','user_id',
    'product_base_price','product_tax','product_unit','product_quantity',
    'opening_stock','defective_quantity','status'];


    public function getProductDescriptionAttribute($name){
        return ucwords($name);
    }


    public function productname(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function product_sale()
    {
        return $this->hasMany(ProductSale::class);
    }

    public function product_taxes()
    {
        //first is the key in the product tax table to join
        return $this->hasMany(ProductTax::class,'product_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_taxes', 'product_id', 'tax_id');
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
        static::creating(function ($Product) {
            $Product->user_id = auth()->id();
            $Product->product_date = date('Y-m-d');
        });
    }
}
