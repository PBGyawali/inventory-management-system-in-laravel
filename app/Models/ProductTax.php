<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    use HasFactory;


    protected $fillable = ['tax_id','product_id'];

    public $timestamps = false; //no time stamps column needed


    protected $hidden = ['password','remember_token'];
}
