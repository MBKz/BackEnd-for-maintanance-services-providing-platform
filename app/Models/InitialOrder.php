<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialOrder extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'description', 'location', 'latitude',
        'longitude', 'job_id', 'state_id', 'city_id',
        'client_id'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function order_gallery()
    {
        return $this->belongsTo(OrderGallery::class);
    }
}
