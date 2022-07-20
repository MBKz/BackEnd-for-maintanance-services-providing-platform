<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'estimation_time', 'estimation_cost', 'note',
        'date', 'service_provider_id', 'initial_order_id', 'state_id',
    ];

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function initial_order()
    {
        return $this->belongsTo(InitialOrder::class);
    }

}
