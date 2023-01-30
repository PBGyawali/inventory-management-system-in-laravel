<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Select;
use Illuminate\Support\Facades\Hash;
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


    public function getCompanyNameAttribute($name){
        return ucwords($name);
    }
    public function setCompanyNameAttribute($name){
        $this->attributes['company_name'] =ucwords($name);
    }
    public function getCompanyBankNameAttribute($name){
        return ucwords($name);
    }
    public function setCompanyBankNameAttribute($name){
        $this->attributes['company_bank_name'] =ucwords($name);
    }
    public function getCompanyBankAdressAttribute($name){
        return ucwords($name);
    }
    public function setCompanyBankAddressAttribute($name){
        $this->attributes['company_bank_address'] =ucwords($name);
    }
    public function setCompanyAddressAttribute($name){
        $this->attributes['company_address'] =ucwords($name);
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
