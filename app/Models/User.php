<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'first_name','last_name','phone_number',
        'email','barthday','gender','image','role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function admin()
    {
    	return $this->hasOne(Admin::class);
    }


    public function serviceProvider()
    {
    	return $this->hasOne(ServiceProvider::class);
    }


    public function client()
    {
    	return $this->hasOne(Client::class);
    }

    public function notifications()
    {
    	return $this->hasMany(Notification::class);
    }

}
