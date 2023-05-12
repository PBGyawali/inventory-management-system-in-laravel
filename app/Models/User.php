<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
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

    protected $rememberTokenName = 'remember_token';


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
        return strtolower($this->user_type)=='user';
    }

    function is_active(){
        return strtolower($this->user_status)=='active';
	}

	function is_admin()	{
        return in_array(strtolower($this->user_type), ['owner', 'admin', 'master']);
	}

    function is_master()	{
        return in_array(strtolower($this->user_type), ['master']);
	}

    function is_same_user($data){
        if ($data instanceof Model) {
            return $this->is($data);
        }
        return $data==$this->id;
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
        else {
            $url_parts = parse_url($value);
            if (isset($url_parts['scheme']) && isset($url_parts['host'])) {
                return $value;
            } 
            elseif (!$value || !Storage::exists($value)) {
                // return a default image if the file does not exist
                return Storage::url('user_profile.png');
            } 
            else {
                // return the URL to the file using the storage facade
                return Storage::url($value);
            }
        }

    }

}
