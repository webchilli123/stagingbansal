<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Permission;
use Laravel\Passport\HasApiTokens;  //add the namespace

class User extends Authenticatable
{
        use HasApiTokens, HasFactory;   //use it here


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false;

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    // user role has permission

    public function hasPermission($name){

        return isset($this->role->permissions) && $this->role->permissions->where('name', $name)->count() > 0
        ? true
        : false;
 

    }

}
