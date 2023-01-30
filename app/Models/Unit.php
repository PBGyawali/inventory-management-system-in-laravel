<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;


    public $timestamps = false; //only want to used created_at column

    protected $primaryKey='unit_id';
    protected $fillable = ['unit_name','unit_status','status'];

    public function getUnitNameAttribute($name){
        return ucwords($name);
    }

    public function setUnitNameAttribute($name){
        $this->attributes['unit_name'] = ucwords($name);
    }
}
