<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey='supplier_id';

    protected $casts = ['created_at' => 'datetime'];

    protected $fillable = ['supplier_name','supplier_status','supplier_email','supplier_contact_no','supplier_address','status'];

    public function setSupplierNameAttribute($name){
        $this->attributes['supplier_name'] = ucwords($name);
    }
}
