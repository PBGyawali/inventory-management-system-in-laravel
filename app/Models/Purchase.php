<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Purchase extends Model
{
    use HasFactory;
    use PowerJoins;

    protected $primaryKey='purchase_id';

    protected $casts = ['created_at' => 'datetime'];
    protected $hidden = ['password','remember_token'];


    protected $fillable = ['purchase_name','purchase_status','purchase_discount','purchase_date',
    'product_base_price','purchase_tax','purchase_sub_total','payment_status',
    'purchase_address','status'];


    public function setPurchaseNameAttribute($name){
        $this->attributes['purchase_name'] = ucwords($name);
    }
    public function getPurchaseNameAttribute($name){
        return ucwords($name);
    }
    public function setPurchaseAddressAttribute($name){
        $this->attributes['purchase_address'] = ucwords($name);
    }
    public function getPurchaseAddressAttribute($name){
        return ucwords($name);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    protected static function booted()
    {
        static::creating(function ($Post) {
            $Post->user_id = auth()->user()->id;

        });
    }
    public function product_purchases()
    {
        return $this->hasMany(ProductPurchase::class,'purchase_id');
    }
}
