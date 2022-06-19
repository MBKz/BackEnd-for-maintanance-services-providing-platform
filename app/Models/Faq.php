<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'question', 'answer', 'tag_id'
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
