<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory ,Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'user_id','device_token'
        ];

    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
