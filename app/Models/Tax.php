<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    public $timestamps = false; //only want to used created_at column

    protected $primaryKey='tax_id';

    protected $fillable = ['tax_name','tax_status','tax_percentage','status'];

    public function setTaxNameAttribute($name){
        $this->attributes['tax_name'] = ucwords($name);
    }
    public function getTaxNameAttribute($name){
        return ucwords($name);
    }
}
