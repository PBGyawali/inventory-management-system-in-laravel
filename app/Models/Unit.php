<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Unit extends Model
{
    use HasFactory;


    public $timestamps = false; //only want to used created_at column

    protected $primaryKey='unit_id';
    protected $fillable = ['unit_name','unit_status','status'];

    public function unitname(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }
}
