<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Получить автора поста
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Получить комментарии к посту блога.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function originalAuthor()
    {
        return $this->belongsTo(User::class, 'creator', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function reposts()
    {
        return $this->hasMany(self::class, 'repost', 'id');
    }
    
}
