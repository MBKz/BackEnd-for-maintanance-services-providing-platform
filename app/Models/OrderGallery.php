<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderGallery extends Model
{
    use HasFactory;

    public $timestamps = false;
        
    protected $fillable = [
        'title', 'image', 'initial_order_id'
    ];

    public function initial_order()
    {
        return $this->hasOne(InitialOrder::class);
    }

}
