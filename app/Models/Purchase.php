<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Purchase extends Model
{
    use HasFactory;


    protected $primaryKey='purchase_id';

    protected $casts = ['created_at' => 'datetime',];
    protected $hidden = ['password','remember_token'];


    protected $fillable = ['purchase_name','purchase_status','purchase_discount','purchase_date',
    'product_base_price','purchase_tax','purchase_sub_total','payment_status',
    'purchase_address','status'];


    public function purchasename(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function purchaseaddress(): Attribute
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

    public function product_purchases()
    {
        return $this->hasMany(ProductPurchase::class,'purchase_id','purchase_id');
    }



    protected static function booted()
    {
        static::creating(function ($data) {
            $data->user_id = auth()->user()->id;

        });
    }
}
