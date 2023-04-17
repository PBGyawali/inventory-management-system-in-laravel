<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Brand extends Model
{
    use HasFactory;



    protected $primaryKey='brand_id';
    protected $fillable = ['brand_name','brand_status','category_id','status'];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'brand_id');
    }

    public function brandname(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }
}
