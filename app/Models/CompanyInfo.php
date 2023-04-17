<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Select;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $fillable =
     [ 'company_name','company_email','company_timezone','company_currency',
        'company_revenue_target','company_sales_target','company_address',
        'currency_symbol','company_contact_no','company_logo','inventory_method',
        'secret_password','company_bank','company_bank_address','company_bank_IBAN'
    ];

    public $timestamps = false;

    protected $hidden = ['secret_password'];
    protected $primaryKey='company_id';

    public function companyName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function companyBank(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function companyBankAddress(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function companyAddress(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    public function setSecretPasswordAttribute($password){
        $this->attributes['secret_password'] = Hash::make($password);
    }

    public function getCompanyLogoAttribute($name){
        if(is_dir(config('app.storage_path').$name)
        ||  !file_exists(config('app.storage_path').$name))
                return '';
        else
                return config('app.storage_url').$name;
    }


    protected static function booted()
    {
        static::creating(function ($info) {
            $info->currency_symbol=Select::Get_currency_symbol($info->company_currency);
        });
    }
}
