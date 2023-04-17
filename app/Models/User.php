<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

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

    protected $casts = ['created_at' => 'datetime'];

    protected $dates = ['created_at'];


    public function setPasswordAttribute($password)
    {
        // Check if the given password is already hashed
        if (Hash::needsRehash($password)) {
                // If it is not hashed, hash it before setting the attribute
                $this->attributes['password'] = Hash::make($password);
        } else {
            // If it is already hashed, don't hash it again
            $this->attributes['password'] = $password;
        }
    }


    public function username(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }

    function is_user(){
        return strtolower($this->user_type)=='user' ? true : false;
    }

    function is_active(){
        return $this->user_status=='active' ? true : false;
	}

	function is_admin()	{
        return in_array(strtolower($this->user_type), ['owner', 'admin', 'master']) ? true : false;
	}

    function is_master()	{
        return in_array(strtolower($this->user_type), ['master']) ? true : false;
	}

    function is_same_user($data)	{
        return $data==$this->id ? true : false;
    }

    public function isAdmin()
    {
        return $this->is_admin();
    }


    public function getProfileImageAttribute($value){
            // image is a remote url return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        elseif(is_dir(config('app.user_images_path').$value)
        ||  !file_exists(config('app.user_images_path').$value))
        //retun the base directory for user images plus image name
                return config('app.user_images_url').'user_profile.png';
        else
                return config('app.user_images_url').$value;
    }

}
