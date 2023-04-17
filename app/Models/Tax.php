<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tax extends Model
{
    use HasFactory;
    public $timestamps = false; //only want to used created_at column

    protected $primaryKey='tax_id';

    protected $fillable = ['tax_name','tax_status','tax_percentage','status'];


    public function taxname(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }


    // public function tax_product()
    // {
    //     //first is the key in the product tax table to join
    //     return $this->hasMany(ProductTax::class,'tax_id');
    // }

    public function products()
{
    return $this->belongsToMany(Product::class, 'product_taxes', 'tax_id', 'product_id');
}

}
