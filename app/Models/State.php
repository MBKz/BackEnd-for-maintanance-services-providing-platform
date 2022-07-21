<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'states';

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
