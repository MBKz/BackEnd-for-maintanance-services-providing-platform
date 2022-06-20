<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'text', 'date', 'service_provider_id'
    ];

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function posts_gallery()
    {
        return $this->hasMany(PostsGallery::class);
    }
}
