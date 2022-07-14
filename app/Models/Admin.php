<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
