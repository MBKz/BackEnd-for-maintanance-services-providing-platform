<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'jobs';

    protected $fillable = [
        'title','description','icon','image'
        ];
}
