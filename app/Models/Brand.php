<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Brand extends Model
{
    use HasFactory;
    use PowerJoins;
    public $timestamps = false;

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

    public function setBrandNameAttribute($name){
        $this->attributes['brand_name'] = ucwords($name);
    }
    public function getBrandNameAttribute($name){
        return ucwords($name);
    }
}
