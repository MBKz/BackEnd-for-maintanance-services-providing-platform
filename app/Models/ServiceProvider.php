<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id','job_id','account_status_id',
        'city_id','rate','num_of_raters','device_token','identity_id'
    ];

    
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
        return $this->belongsTo(Proposal::class);
    }


    public function block()
    {
        return $this->belongsTo(Block::class);
    }

}
