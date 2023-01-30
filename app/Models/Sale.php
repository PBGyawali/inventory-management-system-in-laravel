<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;
class Sale extends Model
{
    use HasFactory;

    use PowerJoins;
    
    protected $primaryKey='sale_id';

    protected $casts = ['created_at' => 'datetime'];

    protected $hidden = ['password','remember_token'];

    protected $fillable = ['sale_name','sale_status','brand_id',
    'category_id','sale_discount','sale_date',
    'product_base_price','sale_tax','sale_sub_total','payment_status',
    'opening_stock','sale_address','status'];

    public function setSaleNameAttribute($name){
        $this->attributes['sale_name'] = ucwords($name);
    }
    public function getSaleNameAttribute($name){
        return ucwords($name);
    }
    public function setSaleAddressAttribute($name){
        $this->attributes['sale_address'] = ucwords($name);
    }
    public function getSaleAddressAttribute($name){
        return ucwords($name);
    }

    protected static function booted()
    {
        static::creating(function ($Post) {
            $Post->user_id = auth()->user()->id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function product_sales()
    {
        return $this->hasMany(ProductSale::class,'sale_id');
    }
}
