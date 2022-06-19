<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'accounts_status';

    public function serrviceProvider()
    {
    	return $this->hasMany(ServiceProvider::class);
    }
}
