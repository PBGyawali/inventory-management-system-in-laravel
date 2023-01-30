<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['username','email','password','user_status','profile_image','user_type','status'];

    protected $hidden = ['password','remember_token'];

    public $timestamps = ["created_at"]; //only want to use created_at column

    const UPDATED_AT = null; //and updated by default null set

    protected $casts = ['created_at' => 'datetime'];

    protected $dates = ['created_at'];

    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }
    public function setUsernameAttribute($name){
        $this->attributes['username'] = ucwords($name);
    }
    public function getUsernameAttribute($name){
        return ucwords($name);
    }

    function is_user(){
        return $this->user_type=='user' ? true : false;
    }

    function is_active(){
        return $this->user_status=='active' ? true : false;
	}

	function is_admin()	{
        return $this->user_type=='master' ? true : false;
	}

    public function isAdmin()
    {
        return $this->is_admin();
    }

}
