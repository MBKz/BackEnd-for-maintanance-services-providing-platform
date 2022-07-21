<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded =['id'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }


}
