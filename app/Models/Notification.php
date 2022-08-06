<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;

    // TODO: ??
    protected $table = 'notifications';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
