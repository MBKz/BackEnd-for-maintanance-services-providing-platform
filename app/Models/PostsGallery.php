<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsGallery extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'title', 'image', 'post_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
