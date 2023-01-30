<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Category extends Model
{
    use HasFactory;
    use PowerJoins;

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
        return $query->orderBy('category_id')->groupBy('category_id');
    }
    public function setCategoryNameAttribute($name){
        $this->attributes['category_name'] = ucwords($name);
    }

    public function getCategoryNameAttribute($name){
        return ucwords($name);
    }
}
