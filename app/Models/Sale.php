<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey='sale_id';

    protected $casts = ['created_at' => 'datetime'];

    protected $hidden = ['password','remember_token'];

    protected $fillable = ['sale_name','sale_status','brand_id',
    'category_id','sale_discount','sale_date',
    'product_base_price','sale_tax','sale_sub_total','payment_status',
    'opening_stock','sale_address','status'];

    public function salename(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function saleaddress(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function product_sales()
    {
        return $this->hasMany(ProductSale::class,'sale_id');
    }


    protected static function booted()
    {
        static::creating(function ($data) {
            $data->user_id = auth()->user()->id;
        });
    }
}
