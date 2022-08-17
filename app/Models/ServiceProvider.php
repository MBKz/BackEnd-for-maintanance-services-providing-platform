<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ServiceProvider extends Model
{
    use HasFactory ,Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'user_id','job_id','account_status_id',
        'city_id','rate','num_of_raters','device_token','identity_id'
    ];


    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account_status()
    {
        return $this->belongsTo(AccountStatus::class);
    }



    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function proposal()
    {
        return $this->hasMany(Proposal::class);
    }

    public function block()
    {
        return $this->hasMany(Block::class);
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    public function post(){
        return $this->hasMany(Post::class);
    }
}
