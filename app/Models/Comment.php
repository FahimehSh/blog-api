<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use softDeletes;

    protected $fillable = [
        'content',
        'author_id',
        'post_id',
        'likes_count',
        'is_published',
        'published_at',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
