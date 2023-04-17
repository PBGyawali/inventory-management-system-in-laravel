<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasFactory;


    public $timestamps = false; //only want to used created_at column

    protected $primaryKey='category_id';
    protected $fillable = ['category_name','category_status','status'];

    public function brands()
    {
        return $this->hasMany(Brand::class,'brand_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }
    public function scopeOrdergroup($query)
    {
        return $query->orderBy('categories.category_id')->groupBy('categories.category_id');
    }

    public function categoryname(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }
    
}
